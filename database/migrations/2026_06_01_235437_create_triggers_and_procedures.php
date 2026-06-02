<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggersAndProcedures extends Migration
{

    public function up(): void
    {
        //TRIGGERS
        //1: Actualizar stock despues de crear un pedido
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
        
        //2: Restaurar stock cuando se cancela un pedido
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
        
        //3: Validar stock antes de agregar al carrito
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
        
        //4: Asignar codigo de acceso automatico para mayoristas
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
        
        //5: Actualizar rango del usuario segun sus compras
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_order_completed;
            CREATE TRIGGER after_order_completed
            AFTER UPDATE ON orders
            FOR EACH ROW
            BEGIN
                DECLARE total_compras DECIMAL(10,2);
                DECLARE nuevo_rango INT;
                
                IF NEW.status = 'delivered' AND OLD.status != 'delivered' THEN
                    SELECT COALESCE(SUM(total), 0) INTO total_compras
                    FROM orders
                    WHERE user_id = NEW.user_id AND status = 'delivered';
                    
                    SELECT id INTO nuevo_rango FROM ranks
                    WHERE monthly_minimum_purchase <= total_compras
                    ORDER BY monthly_minimum_purchase DESC
                    LIMIT 1;
                    
                    IF nuevo_rango IS NOT NULL AND nuevo_rango != (SELECT rank_id FROM users WHERE id = NEW.user_id) THEN
                        UPDATE users 
                        SET rank_id = nuevo_rango,
                            updated_at = NOW()
                        WHERE id = NEW.user_id;
                    END IF;
                END IF;
            END
        ");
        
        //PROCEDIMIENTOS ALMACENADOS
        
        //1: Filtrar productos por tipo de usuario (mayorista/final)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_filter_products_by_user;
            CREATE PROCEDURE sp_filter_products_by_user(
                IN p_user_id INT,
                IN p_category_id INT,
                IN p_brand_id INT,
                IN p_search VARCHAR(100)
            )
            BEGIN
                DECLARE v_user_type VARCHAR(20);
                
                SELECT user_type INTO v_user_type FROM users WHERE id = p_user_id;
                
                SELECT 
                    p.id,
                    p.name,
                    p.description,
                    p.base_price,
                    p.stock,
                    p.image,
                    p.sku,
                    p.warranty_days,
                    b.name as brand_name,
                    c.name as category_name,
                    CASE 
                        WHEN v_user_type = 'mayorista' THEN p.base_price * 0.90
                        ELSE p.base_price
                    END as final_price
                FROM products p
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p_category_id IS NULL OR p.category_id = p_category_id)
                  AND (p_brand_id IS NULL OR p.brand_id = p_brand_id)
                  AND (p_search IS NULL OR p.name LIKE CONCAT('%', p_search, '%') OR p.sku LIKE CONCAT('%', p_search, '%'))
                ORDER BY p.id DESC;
            END
        ");
        
        //2: Reporte de ventas para admin
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
                    SUM(oi.quantity) as total_products_sold,
                    SUM(o.total) as total_sales,
                    AVG(o.total) as average_order_value,
                    COUNT(DISTINCT o.user_id) as unique_customers
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.created_at BETWEEN p_start_date AND p_end_date
                    AND (p_status IS NULL OR o.status = p_status)
                GROUP BY DATE(o.created_at)
                ORDER BY sale_date DESC;
            END
        ");
        
        //3: Productos mas vendidos
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
                    p.base_price,
                    b.name as brand_name,
                    c.name as category_name,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.price_when_ordered) as total_revenue
                FROM products p
                INNER JOIN order_items oi ON p.id = oi.product_id
                INNER JOIN orders o ON oi.order_id = o.id
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p_start_date IS NULL OR o.created_at >= p_start_date)
                    AND (p_end_date IS NULL OR o.created_at <= p_end_date)
                    AND o.status = 'delivered'
                GROUP BY p.id, p.name, p.base_price, b.name, c.name
                ORDER BY total_sold DESC
                LIMIT p_limit;
            END
        ");
        
        //4: Filtrar compras de usuario para admin
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_user_purchases;
            CREATE PROCEDURE sp_user_purchases(
                IN p_user_id INT,
                IN p_status VARCHAR(20),
                IN p_start_date DATE,
                IN p_end_date DATE
            )
            BEGIN
                SELECT 
                    o.id,
                    o.order_number,
                    o.total,
                    o.status,
                    o.payment_method,
                    o.created_at,
                    COUNT(oi.id) as total_items,
                    SUM(oi.quantity) as total_quantity
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = p_user_id
                    AND (p_status IS NULL OR o.status = p_status)
                    AND (p_start_date IS NULL OR DATE(o.created_at) >= p_start_date)
                    AND (p_end_date IS NULL OR DATE(o.created_at) <= p_end_date)
                GROUP BY o.id, o.order_number, o.total, o.status, o.payment_method, o.created_at
                ORDER BY o.created_at DESC;
            END
        ");
        
        //5: Dashboard ejecutivo para admin
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_admin_dashboard;
            CREATE PROCEDURE sp_admin_dashboard()
            BEGIN
                SELECT 
                    (SELECT COUNT(*) FROM users) as total_users,
                    (SELECT COUNT(*) FROM users WHERE user_type = 'mayorista') as total_wholesalers,
                    (SELECT COUNT(*) FROM users WHERE user_type = 'final') as total_final_customers,
                    (SELECT COUNT(*) FROM products) as total_products,
                    (SELECT SUM(stock) FROM products) as total_stock,
                    (SELECT COUNT(*) FROM products WHERE stock <= 5) as low_stock_products;
                    
                SELECT 
                    COALESCE(SUM(total), 0) as monthly_sales,
                    COUNT(*) as monthly_orders
                FROM orders
                WHERE MONTH(created_at) = MONTH(NOW())
                    AND YEAR(created_at) = YEAR(NOW())
                    AND status = 'delivered';
                    
                SELECT 
                    o.id,
                    o.order_number,
                    u.name as customer_name,
                    o.total,
                    o.status,
                    o.created_at
                FROM orders o
                INNER JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5;
                
                SELECT 
                    c.name as category_name,
                    SUM(oi.quantity) as total_sold
                FROM categories c
                INNER JOIN products p ON c.id = p.category_id
                INNER JOIN order_items oi ON p.id = oi.product_id
                INNER JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'delivered'
                GROUP BY c.id, c.name
                ORDER BY total_sold DESC
                LIMIT 3;
            END
        ");
    }

    public function down(): void
    {
        // Eliminar triggers
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_item_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_status_update");
        DB::unprepared("DROP TRIGGER IF EXISTS before_cart_item_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_user_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_completed");
        
        // Eliminar procedimientos almacenados
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_filter_products_by_user");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_sales_report");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_top_products");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_user_purchases");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_admin_dashboard");
    }
}