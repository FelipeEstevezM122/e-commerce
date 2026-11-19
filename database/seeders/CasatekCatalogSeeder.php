<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CasatekCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Localizar el archivo JSON del catálogo.
        $ruta = database_path('seeders/data/casatek_catalogo.json');

        if (!is_file($ruta)) {
            throw new RuntimeException(
                'No se encontró el archivo del catálogo CASATEK.'
            );
        }

        // 2. Leer y validar el JSON.
        $contenido = file_get_contents($ruta);

        if ($contenido === false) {
            throw new RuntimeException(
                'No se pudo leer el archivo del catálogo.'
            );
        }

        $catalogo = json_decode(
            $contenido,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach (['brands', 'categories', 'products'] as $clave) {
            if (!isset($catalogo[$clave]) || !is_array($catalogo[$clave])) {
                throw new RuntimeException(
                    "Falta la sección {$clave} en el catálogo."
                );
            }
        }

        // 3. Comprobar las referencias y los SKU antes de escribir.
        $idsMarcas = array_column($catalogo['brands'], 'id');
        $idsCategorias = array_column($catalogo['categories'], 'id');
        $skus = [];

        foreach ($catalogo['products'] as $producto) {
            $sku = trim((string) ($producto['sku'] ?? ''));

            if ($sku === '' || isset($skus[$sku])) {
                throw new RuntimeException(
                    "SKU vacío o duplicado: {$sku}"
                );
            }

            $skus[$sku] = true;

            if (
                !in_array($producto['brand_id'] ?? null, $idsMarcas, true) ||
                !in_array($producto['category_id'] ?? null, $idsCategorias, true)
            ) {
                throw new RuntimeException(
                    "Marca o categoría inválida para el SKU {$sku}."
                );
            }
        }

        // 4. Realizar las inserciones dentro de una transacción.
        DB::transaction(function () use ($catalogo) {

            $mapaMarcas = [];
            $mapaCategorias = [];

            $marcasCreadas = 0;
            $categoriasCreadas = 0;
            $productosCreados = 0;
            $productosExistentes = 0;

            // 5. Registrar únicamente las marcas que no existan.
            foreach ($catalogo['brands'] as $marca) {
                $registro = Brand::firstOrCreate(
                    ['name' => $marca['name']],
                    [
                        'description' => $marca['description'] ?? null,
                    ]
                );

                $mapaMarcas[$marca['id']] = $registro->id;

                if ($registro->wasRecentlyCreated) {
                    $marcasCreadas++;
                }
            }

            // 6. Registrar únicamente las categorías que no existan.
            foreach ($catalogo['categories'] as $categoria) {
                $registro = Category::firstOrCreate(
                    ['name' => $categoria['name']],
                    [
                        'description' => $categoria['description'] ?? null,
                    ]
                );

                $mapaCategorias[$categoria['id']] = $registro->id;

                if ($registro->wasRecentlyCreated) {
                    $categoriasCreadas++;
                }
            }

            // 7. Registrar solamente los productos faltantes.
            foreach ($catalogo['products'] as $producto) {

                $registro = Product::firstOrCreate(
                    ['sku' => $producto['sku']],
                    [
                        'name' => $producto['name'],
                        'description' => $producto['description'] ?? null,
                        'base_price' => $producto['base_price'],
                        'stock' => $producto['stock'],
                        'warranty_days' => $producto['warranty_days'] ?? null,
                        'image1' => $producto['image1'] ?? null,
                        'image2' => $producto['image2'] ?? null,
                        'image3' => $producto['image3'] ?? null,
                        'image4' => $producto['image4'] ?? null,

                        // Usar los IDs de esta base de datos,
                        // no los IDs originales de Docker.
                        'brand_id' => $mapaMarcas[$producto['brand_id']],
                        'category_id' => $mapaCategorias[$producto['category_id']],
                    ]
                );

                if ($registro->wasRecentlyCreated) {
                    $productosCreados++;
                } else {
                    $productosExistentes++;
                }
            }

            // 8. Mostrar un resumen de la operación.
            $this->command->info('Catálogo CASATEK procesado.');
            $this->command->info("Marcas creadas: {$marcasCreadas}");
            $this->command->info("Categorías creadas: {$categoriasCreadas}");
            $this->command->info("Productos creados: {$productosCreados}");
            $this->command->info("Productos ya existentes: {$productosExistentes}");
        });
    }
}
