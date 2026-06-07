<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════════════
        //  PROCEDIMIENTOS ALMACENADOS
        // ═══════════════════════════════════════════════════════════════

        // SP 1 ─ Filtrar productos (usuarios: catálogo público)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_filtrar_productos');
        DB::unprepared("
CREATE PROCEDURE sp_filtrar_productos(
    IN p_nombre      VARCHAR(150),
    IN p_category_id INT,
    IN p_brand_id    INT,
    IN p_orden       VARCHAR(20),
    IN p_per_page    INT,
    IN p_offset      INT
)
BEGIN
    SET p_nombre      = IFNULL(p_nombre, '');
    SET p_per_page    = IFNULL(p_per_page, 8);
    SET p_offset      = IFNULL(p_offset, 0);

    SELECT
        p.id,
        p.name,
        p.description,
        p.base_price,
        p.stock,
        p.sku,
        p.image1, p.image2, p.image3, p.image4,
        p.warranty_days,
        p.brand_id,
        p.category_id,
        b.name  AS brand_name,
        c.name  AS category_name
    FROM products p
    LEFT JOIN brands     b ON b.id = p.brand_id
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE
        (p_nombre      = ''   OR p.name LIKE CONCAT('%', p_nombre, '%'))
        AND (p_category_id IS NULL OR p.category_id = p_category_id)
        AND (p_brand_id    IS NULL OR p.brand_id    = p_brand_id)
    ORDER BY
        CASE WHEN p_orden = 'nombre'      THEN p.name           END ASC,
        CASE WHEN p_orden = 'precio_asc'  THEN p.base_price     END ASC,
        CASE WHEN p_orden = 'precio_desc' THEN p.base_price     END DESC,
        CASE WHEN p_orden = 'marca'       THEN b.name           END ASC,
        CASE WHEN p_orden = 'categoria'   THEN c.name           END ASC,
        CASE WHEN p_orden = 'default' OR p_orden IS NULL THEN p.created_at END DESC
    LIMIT p_per_page OFFSET p_offset;
END
        ");

        // SP 2 ─ Filtrar productos admin (panel administrador)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_productos');
        DB::unprepared("
CREATE PROCEDURE sp_admin_filtrar_productos(
    IN p_search      VARCHAR(150),
    IN p_category_id INT,
    IN p_brand_id    INT,
    IN p_stock_min   INT,
    IN p_stock_max   INT,
    IN p_orden       VARCHAR(20),
    IN p_per_page    INT,
    IN p_offset      INT
)
BEGIN
    SET p_search   = IFNULL(p_search, '');
    SET p_per_page = IFNULL(p_per_page, 15);
    SET p_offset   = IFNULL(p_offset, 0);

    SELECT
        p.id, p.name, p.description, p.base_price, p.stock, p.sku,
        p.image1, p.warranty_days, p.brand_id, p.category_id,
        p.created_at,
        b.name AS brand_name,
        c.name AS category_name
    FROM products p
    LEFT JOIN brands     b ON b.id = p.brand_id
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE
        (p_search      = ''   OR p.name LIKE CONCAT('%', p_search, '%')
                              OR p.description LIKE CONCAT('%', p_search, '%')
                              OR p.sku LIKE CONCAT('%', p_search, '%'))
        AND (p_category_id IS NULL OR p.category_id = p_category_id)
        AND (p_brand_id    IS NULL OR p.brand_id    = p_brand_id)
        AND (p_stock_min   IS NULL OR p.stock >= p_stock_min)
        AND (p_stock_max   IS NULL OR p.stock <= p_stock_max)
    ORDER BY
        CASE WHEN p_orden = 'nombre'      THEN p.name       END ASC,
        CASE WHEN p_orden = 'precio_asc'  THEN p.base_price END ASC,
        CASE WHEN p_orden = 'precio_desc' THEN p.base_price END DESC,
        CASE WHEN p_orden = 'stock_asc'   THEN p.stock      END ASC,
        CASE WHEN p_orden = 'stock_desc'  THEN p.stock      END DESC,
        CASE WHEN p_orden IS NULL OR p_orden = 'reciente' THEN p.created_at END DESC
    LIMIT p_per_page OFFSET p_offset;
END
        ");

        // SP 3 ─ Filtrar pedidos admin
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_pedidos');
        DB::unprepared("
CREATE PROCEDURE sp_admin_filtrar_pedidos(
    IN p_search    VARCHAR(150),
    IN p_status    VARCHAR(20),
    IN p_user_id   INT,
    IN p_from_date DATE,
    IN p_to_date   DATE,
    IN p_per_page  INT,
    IN p_offset    INT
)
BEGIN
    SET p_search   = IFNULL(p_search, '');
    SET p_per_page = IFNULL(p_per_page, 15);
    SET p_offset   = IFNULL(p_offset, 0);

    SELECT
        o.id, o.order_number, o.total, o.status,
        o.payment_method, o.created_at,
        u.id   AS user_id,
        u.name AS user_name,
        u.email AS user_email,
        (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS items_count
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id
    WHERE
        (p_search  = ''   OR o.order_number LIKE CONCAT('%', p_search, '%')
                          OR u.name  LIKE CONCAT('%', p_search, '%')
                          OR u.email LIKE CONCAT('%', p_search, '%'))
        AND (p_status  IS NULL OR o.status    = p_status)
        AND (p_user_id IS NULL OR o.user_id   = p_user_id)
        AND (p_from_date IS NULL OR DATE(o.created_at) >= p_from_date)
        AND (p_to_date   IS NULL OR DATE(o.created_at) <= p_to_date)
    ORDER BY o.created_at DESC
    LIMIT p_per_page OFFSET p_offset;
END
        ");

        // SP 4 ─ Filtrar usuarios admin
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_usuarios');
        DB::unprepared("
CREATE PROCEDURE sp_admin_filtrar_usuarios(
    IN p_search    VARCHAR(150),
    IN p_role      VARCHAR(50),
    IN p_rank_id   INT,
    IN p_per_page  INT,
    IN p_offset    INT
)
BEGIN
    SET p_search   = IFNULL(p_search, '');
    SET p_per_page = IFNULL(p_per_page, 15);
    SET p_offset   = IFNULL(p_offset, 0);

    SELECT
        u.id, u.name, u.email, u.phone, u.created_at,
        r.name   AS rank_name,
        r.discount_percent,
        GROUP_CONCAT(ro.name ORDER BY ro.name SEPARATOR ', ') AS roles,
        (SELECT COUNT(*) FROM orders ord WHERE ord.user_id = u.id) AS total_orders
    FROM users u
    LEFT JOIN ranks r  ON r.id  = u.rank_id
    LEFT JOIN user_role ur ON ur.user_id = u.id
    LEFT JOIN roles ro ON ro.id = ur.role_id
    WHERE
        (p_search  = ''   OR u.name  LIKE CONCAT('%', p_search, '%')
                          OR u.email LIKE CONCAT('%', p_search, '%')
                          OR u.phone LIKE CONCAT('%', p_search, '%'))
        AND (p_rank_id IS NULL OR u.rank_id = p_rank_id)
        AND (p_role    IS NULL OR EXISTS (
                SELECT 1 FROM user_role ur2
                JOIN roles r2 ON r2.id = ur2.role_id
                WHERE ur2.user_id = u.id AND r2.name = p_role
            ))
    GROUP BY u.id, u.name, u.email, u.phone, u.created_at, r.name, r.discount_percent
    ORDER BY u.created_at DESC
    LIMIT p_per_page OFFSET p_offset;
END
        ");

        // SP 5 ─ Filtrar tickets admin
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_tickets');
        DB::unprepared("
CREATE PROCEDURE sp_admin_filtrar_tickets(
    IN p_search    VARCHAR(150),
    IN p_from_date DATE,
    IN p_to_date   DATE,
    IN p_per_page  INT,
    IN p_offset    INT
)
BEGIN
    SET p_search   = IFNULL(p_search, '');
    SET p_per_page = IFNULL(p_per_page, 15);
    SET p_offset   = IFNULL(p_offset, 0);

    SELECT
        t.id, t.ticket_number, t.issued_at,
        o.id           AS order_id,
        o.order_number,
        o.total,
        o.status       AS order_status,
        u.id           AS user_id,
        u.name         AS user_name,
        u.email        AS user_email
    FROM tickets t
    JOIN orders o ON o.id = t.order_id
    LEFT JOIN users u ON u.id = o.user_id
    WHERE
        (p_search = '' OR t.ticket_number LIKE CONCAT('%', p_search, '%')
                       OR o.order_number  LIKE CONCAT('%', p_search, '%')
                       OR u.name          LIKE CONCAT('%', p_search, '%')
                       OR u.email         LIKE CONCAT('%', p_search, '%'))
        AND (p_from_date IS NULL OR DATE(t.issued_at) >= p_from_date)
        AND (p_to_date   IS NULL OR DATE(t.issued_at) <= p_to_date)
    ORDER BY t.issued_at DESC
    LIMIT p_per_page OFFSET p_offset;
END
        ");

        // SP 6 ─ Reporte de ventas (ya existía en el proyecto, se mantiene)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_sales_report');
        DB::unprepared("
CREATE PROCEDURE sp_sales_report(
    IN p_start_date DATE,
    IN p_end_date   DATE,
    IN p_status     VARCHAR(20)
)
BEGIN
    SET p_status = IFNULL(p_status, 'delivered');
    SELECT
        DATE(o.created_at)    AS sale_date,
        COUNT(*)              AS total_orders,
        SUM(o.total)          AS total_revenue,
        AVG(o.total)          AS avg_order_value,
        COUNT(DISTINCT o.user_id) AS unique_customers
    FROM orders o
    WHERE o.status = p_status
      AND DATE(o.created_at) BETWEEN p_start_date AND p_end_date
    GROUP BY DATE(o.created_at)
    ORDER BY sale_date ASC;
END
        ");

        // SP 7 ─ Top productos (ya existía)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_top_products');
        DB::unprepared("
CREATE PROCEDURE sp_top_products(
    IN p_limit      INT,
    IN p_start_date DATE,
    IN p_end_date   DATE
)
BEGIN
    SET p_limit = IFNULL(p_limit, 10);
    SELECT
        p.id, p.name, p.base_price, p.stock,
        b.name AS brand_name, c.name AS category_name,
        SUM(oi.quantity)        AS total_sold,
        SUM(oi.quantity * oi.unit_price) AS total_revenue
    FROM order_items oi
    JOIN orders o   ON o.id  = oi.order_id
    JOIN products p ON p.id  = oi.product_id
    LEFT JOIN brands     b ON b.id = p.brand_id
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE o.status = 'delivered'
      AND (p_start_date IS NULL OR DATE(o.created_at) >= p_start_date)
      AND (p_end_date   IS NULL OR DATE(o.created_at) <= p_end_date)
    GROUP BY p.id, p.name, p.base_price, p.stock, b.name, c.name
    ORDER BY total_sold DESC
    LIMIT p_limit;
END
        ");

        // ═══════════════════════════════════════════════════════════════
        //  TRIGGERS
        // ═══════════════════════════════════════════════════════════════

        // TRIGGER 1 ─ Registrar log cuando se actualiza el estado de un pedido
        DB::unprepared('DROP TABLE IF EXISTS order_status_logs');
        DB::statement("
CREATE TABLE order_status_logs (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id   BIGINT UNSIGNED NOT NULL,
    old_status VARCHAR(20),
    new_status VARCHAR(20) NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_order_status');
        DB::unprepared("
CREATE TRIGGER trg_log_order_status
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO order_status_logs (order_id, old_status, new_status, changed_at)
        VALUES (NEW.id, OLD.status, NEW.status, NOW());
    END IF;
END
        ");

        // TRIGGER 2 ─ Reducir stock al insertar un order_item
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_on_order_item');
        DB::unprepared("
CREATE TRIGGER trg_reduce_stock_on_order_item
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id AND stock >= NEW.quantity;
END
        ");

        // TRIGGER 3 ─ Restaurar stock si se cancela un pedido
        DB::unprepared('DROP TRIGGER IF EXISTS trg_restore_stock_on_cancel');
        DB::unprepared("
CREATE TRIGGER trg_restore_stock_on_cancel
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'cancelled' AND OLD.status <> 'cancelled' THEN
        UPDATE products p
        JOIN order_items oi ON oi.product_id = p.id
        SET p.stock = p.stock + oi.quantity
        WHERE oi.order_id = NEW.id;
    END IF;
END
        ");

        // TRIGGER 4 ─ Generar ticket automáticamente cuando un pedido pasa a 'delivered'
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auto_ticket_on_delivered');
        DB::unprepared("
CREATE TRIGGER trg_auto_ticket_on_delivered
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'delivered' AND OLD.status <> 'delivered' THEN
        IF NOT EXISTS (SELECT 1 FROM tickets WHERE order_id = NEW.id) THEN
            INSERT INTO tickets (order_id, ticket_number, issued_at, created_at, updated_at)
            VALUES (
                NEW.id,
                CONCAT('TKT-', LPAD(
                    (SELECT IFNULL(MAX(id), 0) + 1 FROM tickets AS t2),
                    8, '0'
                )),
                NOW(), NOW(), NOW()
            );
        END IF;
    END IF;
END
        ");

        // TRIGGER 5 ─ Actualizar acumulado de compras al marcar pedido como 'delivered'
        DB::unprepared('DROP TRIGGER IF EXISTS trg_update_accumulated_purchases');
        DB::unprepared("
CREATE TRIGGER trg_update_accumulated_purchases
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'delivered' AND OLD.status <> 'delivered' THEN
        INSERT INTO accumulated_purchases (user_id, total_amount, created_at, updated_at)
        VALUES (NEW.user_id, NEW.total, NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            total_amount = total_amount + NEW.total,
            updated_at   = NOW();
    END IF;
END
        ");

        // TRIGGER 6 ─ Prevenir eliminación de un producto con pedidos activos
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_product_delete_with_active_orders');
        DB::unprepared("
CREATE TRIGGER trg_prevent_product_delete_with_active_orders
BEFORE DELETE ON products
FOR EACH ROW
BEGIN
    DECLARE active_count INT;
    SELECT COUNT(*) INTO active_count
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    WHERE oi.product_id = OLD.id
      AND o.status NOT IN ('delivered', 'cancelled');

    IF active_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede eliminar: el producto tiene pedidos activos en curso.';
    END IF;
END
        ");

        // TRIGGER 7 ─ Impedir stock negativo en actualizaciones directas
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_negative_stock');
        DB::unprepared("
CREATE TRIGGER trg_prevent_negative_stock
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El stock no puede ser negativo.';
    END IF;
END
        ");
    }

    public function down(): void
    {
        // Triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_negative_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_product_delete_with_active_orders');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_update_accumulated_purchases');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auto_ticket_on_delivered');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_restore_stock_on_cancel');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_on_order_item');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_order_status');
        DB::statement('DROP TABLE IF EXISTS order_status_logs');

        // Stored Procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_top_products');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_sales_report');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_tickets');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_usuarios');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_pedidos');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_filtrar_productos');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_filtrar_productos');
    }
};
