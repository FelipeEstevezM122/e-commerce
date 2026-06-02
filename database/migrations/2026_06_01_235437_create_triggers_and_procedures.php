<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggersAndProcedures extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ============ TRIGGERS ============
        
        // Trigger 1: Actualizar stock después de crear un pedido
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_order_item_insert;
            CREATE TRIGGER after_order_item_insert
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                UPDATE products 
                SET stock = stock - NEW.quantity,
                    updated_at = NOW()
                WHERE id = NEW.product_id;
            END
        ");
        
        // Trigger 2: Restaurar stock cuando se cancela un pedido
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_order_status_update;
            CREATE TRIGGER after_order_status_update
            AFTER UPDATE ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'cancelled' AND OLD.status != 'cancelled' THEN
                    UPDATE products p
                    INNER JOIN order_items oi ON p.id = oi.product_id
                    SET p.stock = p.stock + oi.quantity,
                        p.updated_at = NOW()
                    WHERE oi.order_id = NEW.id;
                END IF;
            END
        ");
        
        // Trigger 3: Validar stock antes de insertar en carrito
        DB::unprepared("
            DROP TRIGGER IF EXISTS before_cart_item_insert;
            CREATE TRIGGER before_cart_item_insert
            BEFORE INSERT ON cart_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                
                SELECT stock INTO available_stock 
                FROM products 
                WHERE id = NEW.product_id;
                
                IF available_stock < NEW.quantity THEN
                    SIGNAL SQLSTATE '45000' 
                    SET MESSAGE_TEXT = 'Stock insuficiente para agregar al carrito';
                END IF;
            END
        ");
        
        // Trigger 4: Asignar código de acceso automático para mayoristas
        DB::unprepared("
            DROP TRIGGER IF EXISTS before_user_insert;
            CREATE TRIGGER before_user_insert
            BEFORE INSERT ON users
            FOR EACH ROW
            BEGIN
                IF NEW.user_type = 'mayorista' AND (NEW.access_code IS NULL OR NEW.access_code = '') THEN
                    SET NEW.access_code = LPAD(FLOOR(RAND() * 999999), 6, '0');
                END IF;
            END
        ");
        
        // Trigger 5: Actualizar rango del usuario según sus compras (NUEVO TRIGGER)
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_order_completed;
            CREATE TRIGGER after_order_completed
            AFTER UPDATE ON orders
            FOR EACH ROW
            BEGIN
                DECLARE total_compras DECIMAL(10,2);
                DECLARE nuevo_rango INT;
                
                -- Solo ejecutar cuando el pedido cambia a 'completed'
                IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
                    -- Calcular total de compras del usuario
                    SELECT COALESCE(SUM(total), 0) INTO total_compras
                    FROM orders
                    WHERE user_id = NEW.user_id AND status = 'completed';
                    
                    -- Determinar el rango según el monto total
                    SELECT id INTO nuevo_rango FROM ranks
                    WHERE min_purchase <= total_compras
                    ORDER BY min_purchase DESC
                    LIMIT 1;
                    
                    -- Actualizar el rango del usuario si es necesario
                    IF nuevo_rango IS NOT NULL AND nuevo_rango != (SELECT rank_id FROM users WHERE id = NEW.user_id) THEN
                        UPDATE users 
                        SET rank_id = nuevo_rango,
                            updated_at = NOW()
                        WHERE id = NEW.user_id;
                    END IF;
                END IF;
            END
        ");
        
        // ============ PROCEDIMIENTOS ALMACENADOS ============
        
        // Procedimiento 1: Reporte de ventas
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_sales_report;
            CREATE PROCEDURE sp_sales_report(
                IN p_start_date DATE,
                IN p_end_date DATE,
                IN p_status VARCHAR(20)
            )
            BEGIN
                SELECT 
                    DATE(o.created_at) as sale_date,
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(oi.quantity) as total_quantity,
                    SUM(o.total) as total_sales,
                    AVG(o.total) as average_order_value
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.created_at BETWEEN p_start_date AND p_end_date
                    AND (p_status IS NULL OR o.status = p_status)
                GROUP BY DATE(o.created_at)
                ORDER BY sale_date DESC;
            END
        ");
        
        // Procedimiento 2: Top productos más vendidos
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_top_products;
            CREATE PROCEDURE sp_top_products(
                IN p_limit INT,
                IN p_start_date DATE,
                IN p_end_date DATE
            )
            BEGIN
                SELECT 
                    p.id,
                    p.name,
                    p.price,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.price_when_ordered) as total_revenue
                FROM products p
                INNER JOIN order_items oi ON p.id = oi.product_id
                INNER JOIN orders o ON oi.order_id = o.id
                WHERE (p_start_date IS NULL OR o.created_at >= p_start_date)
                    AND (p_end_date IS NULL OR o.created_at <= p_end_date)
                    AND o.status = 'completed'
                GROUP BY p.id, p.name, p.price
                ORDER BY total_sold DESC
                LIMIT p_limit;
            END
        ");
        
        // Procedimiento 3: Estadísticas de clientes
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_customer_statistics;
            CREATE PROCEDURE sp_customer_statistics(
                IN p_min_orders INT,
                IN p_min_spent DECIMAL(10,2)
            )
            BEGIN
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    u.user_type,
                    COUNT(o.id) as total_orders,
                    SUM(o.total) as total_spent,
                    AVG(o.total) as average_order,
                    MAX(o.created_at) as last_purchase_date
                FROM users u
                LEFT JOIN orders o ON u.id = o.user_id AND o.status = 'completed'
                GROUP BY u.id, u.name, u.email, u.user_type
                HAVING total_orders >= COALESCE(p_min_orders, 0)
                    AND total_spent >= COALESCE(p_min_spent, 0)
                ORDER BY total_spent DESC;
            END
        ");
        
        // Procedimiento 4: Gestión de inventario
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_inventory_management;
            CREATE PROCEDURE sp_inventory_management(
                IN p_stock_threshold INT
            )
            BEGIN
                SELECT 
                    p.id,
                    p.name,
                    p.stock,
                    p_stock_threshold as minimum_stock,
                    CASE 
                        WHEN p.stock = 0 THEN 'CRITICAL'
                        WHEN p.stock <= p_stock_threshold/2 THEN 'URGENT'
                        ELSE 'WARNING'
                    END as alert_level
                FROM products p
                WHERE p.stock <= p_stock_threshold
                ORDER BY p.stock ASC;
            END
        ");
        
        // Procedimiento 5: Dashboard ejecutivo
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_executive_dashboard;
            CREATE PROCEDURE sp_executive_dashboard(
                IN p_days INT
            )
            BEGIN
                SELECT 
                    'Resumen del período' as section,
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(o.total) as total_sales,
                    COUNT(DISTINCT o.user_id) as active_customers,
                    AVG(o.total) as avg_order_value
                FROM orders o
                WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL p_days DAY)
                    AND o.status = 'completed';
                    
                SELECT 
                    'Top 5 Productos' as section,
                    p.name,
                    SUM(oi.quantity) as units_sold,
                    SUM(oi.quantity * oi.price_when_ordered) as revenue
                FROM products p
                INNER JOIN order_items oi ON p.id = oi.product_id
                INNER JOIN orders o ON oi.order_id = o.id
                WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL p_days DAY)
                    AND o.status = 'completed'
                GROUP BY p.id, p.name
                ORDER BY units_sold DESC
                LIMIT 5;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar triggers
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_item_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_status_update");
        DB::unprepared("DROP TRIGGER IF EXISTS before_cart_item_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_user_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_completed"); // Nuevo trigger
        
        // Eliminar procedimientos
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_sales_report");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_top_products");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_customer_statistics");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_inventory_management");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_executive_dashboard");
    }
}