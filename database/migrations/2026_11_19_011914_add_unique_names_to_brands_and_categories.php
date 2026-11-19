
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Detectar nombres duplicados antes de modificar las tablas.
        foreach (['brands', 'categories'] as $tabla) {
            $duplicados = DB::table($tabla)
                ->selectRaw('LOWER(TRIM(name)) AS nombre_normalizado, COUNT(*) AS total')
                ->groupByRaw('LOWER(TRIM(name))')
                ->havingRaw('COUNT(*) > 1')
                ->first();

            if ($duplicados) {
                throw new RuntimeException(
                    "No se puede agregar UNIQUE a {$tabla}: existen nombres duplicados. " .
                    "Revisa los registros antes de ejecutar esta migración."
                );
            }
        }

        // 2. Impedir nombres duplicados en marcas.
        Schema::table('brands', function (Blueprint $table) {
            $table->unique('name', 'brands_name_unique');
        });

        // 3. Impedir nombres duplicados en categorías.
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name', 'categories_name_unique');
        });
    }

    public function down(): void
    {
        // Revertir solamente los índices que crea esta migración.
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_name_unique');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropUnique('brands_name_unique');
        });
    }
};
