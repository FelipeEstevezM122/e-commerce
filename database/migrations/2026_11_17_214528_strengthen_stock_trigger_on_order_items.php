
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_on_order_item');

        DB::unprepared("
            CREATE TRIGGER trg_reduce_stock_on_order_item
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                IF NEW.quantity IS NULL OR NEW.quantity <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'La cantidad debe ser mayor a cero';
                END IF;

                UPDATE products
                SET stock = stock - NEW.quantity
                WHERE id = NEW.product_id
                  AND stock >= NEW.quantity;

                IF ROW_COUNT() = 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Stock insuficiente para realizar la reserva';
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_on_order_item');

        DB::unprepared("
            CREATE TRIGGER trg_reduce_stock_on_order_item
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET stock = stock - NEW.quantity
                WHERE id = NEW.product_id
                  AND stock >= NEW.quantity;
            END
        ");
    }
};
