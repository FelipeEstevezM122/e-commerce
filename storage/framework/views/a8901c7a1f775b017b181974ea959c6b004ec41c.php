<?php $__env->startSection('titulo', 'Catálogo de Productos - Casatek'); ?>

<?php $__env->startSection('contenido'); ?>

<style>
    #productModal { display: none; }
    #productModal.open { display: flex; }
</style>

<section class="py-6 space-y-6 text-gray-800 dark:text-gray-100 max-w-6xl mx-auto px-4">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-4 border-gray-200 dark:border-gray-700">

        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Catálogo de Productos</h2>

        <form method="GET" action="<?php echo e(route('productos')); ?>" id="filtroForm"
              class="flex items-center gap-2 w-full md:w-auto flex-wrap">

            <input id="searchInput" name="q" type="text" value="<?php echo e(request('q')); ?>"
                   placeholder="Buscar productos..."
                   class="flex-1 min-w-[160px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-white focus:outline-none focus:border-[#1b803a]">

            <select name="category_id" onchange="document.getElementById('filtroForm').submit()"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-gray-200">
                <option value="">Todas las categorías</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="brand_id" onchange="document.getElementById('filtroForm').submit()"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-gray-200">
                <option value="">Todas las marcas</option>
                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($brand->id); ?>" <?php echo e(request('brand_id') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-[#1b803a] hover:bg-[#166a30] text-white text-sm rounded-md">
                Buscar
            </button>

            <?php if(request('q') || request('category_id') || request('brand_id')): ?>
                <a href="<?php echo e(route('productos')); ?>" class="text-sm text-gray-500 hover:text-red-500">Limpiar</a>
            <?php endif; ?>
        </form>

        <button id="cartViewBtn" class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium">
            Carrito
            <span id="cartBadge" class="bg-[#1b803a] text-white text-xs rounded-full px-2">0</span>
        </button>
    </div>

    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $imagen  = $producto->image1 ?? $producto->image2 ?? $producto->image3 ?? 'https://via.placeholder.com/400x300?text=Sin+imagen';
            $marca   = $producto->brand->name    ?? 'Sin marca';
            $categ   = $producto->category->name ?? 'General';
        ?>

        <div class="border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden flex flex-col cursor-pointer"
             data-id="<?php echo e($producto->id); ?>"
             data-nombre="<?php echo e($producto->name); ?>"
             data-marca="<?php echo e($marca); ?>"
             data-categoria="<?php echo e($categ); ?>"
             data-precio="<?php echo e($producto->base_price); ?>"
             data-descripcion="<?php echo e($producto->description ?? 'Sin descripción.'); ?>"
             data-img="<?php echo e($imagen); ?>"
             data-stock="<?php echo e($producto->stock); ?>"
             data-sku="<?php echo e($producto->sku); ?>"
             onclick="abrirModal(this)">

            <div class="w-full h-40 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                <img src="<?php echo e($imagen); ?>" alt="<?php echo e($producto->name); ?>"
                     class="w-full h-full object-contain p-3"
                     onerror="this.src='https://via.placeholder.com/400x300?text=Sin+imagen'">
            </div>

            <div class="p-3 flex-1 flex flex-col gap-2">
                <span class="text-xs text-gray-400 uppercase"><?php echo e($categ); ?> · <?php echo e($marca); ?></span>
                <h3 class="font-medium text-sm text-gray-900 dark:text-white line-clamp-1"><?php echo e($producto->name); ?></h3>
                <span class="text-base font-bold text-gray-900 dark:text-green-400">
                    <?php echo e(number_format($producto->base_price, 2)); ?> Bs.
                </span>

                <button onclick="event.stopPropagation(); agregarAlCarrito(this.closest('[data-id]'))"
                        <?php echo e($producto->stock <= 0 ? 'disabled' : ''); ?>

                        class="mt-auto w-full border border-gray-300 dark:border-gray-600 text-sm py-1.5 rounded-md disabled:opacity-40">
                    <?php echo e($producto->stock <= 0 ? 'Sin stock' : 'Agregar al carrito'); ?>

                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-4 text-center py-16 text-gray-400">
            <p class="font-medium">No se encontraron productos</p>
            <a href="<?php echo e(route('productos')); ?>" class="mt-2 inline-block text-[#1b803a] hover:underline text-sm">Ver todos los productos</a>
        </div>
        <?php endif; ?>
    </div>

    <?php if($productos->hasPages()): ?>
        <div class="flex items-center justify-center gap-2 pt-4">
            <?php if($productos->onFirstPage()): ?>
                <span class="px-3 py-1 text-sm text-gray-300">«</span>
            <?php else: ?>
                <a href="<?php echo e($productos->previousPageUrl()); ?>" class="px-3 py-1 text-sm border rounded-md">«</a>
            <?php endif; ?>
            <?php $__currentLoopData = $productos->getUrlRange(1, $productos->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $productos->currentPage()): ?>
                    <span class="px-3 py-1 text-sm border rounded-md bg-[#1b803a] text-white"><?php echo e($page); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($url); ?>" class="px-3 py-1 text-sm border rounded-md"><?php echo e($page); ?></a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($productos->hasMorePages()): ?>
                <a href="<?php echo e($productos->nextPageUrl()); ?>" class="px-3 py-1 text-sm border rounded-md">»</a>
            <?php else: ?>
                <span class="px-3 py-1 text-sm text-gray-300">»</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</section>

<div id="productModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center p-4" onclick="cerrarModal(event)">
    <div class="bg-white dark:bg-gray-900 rounded-md shadow-lg w-full max-w-md relative p-5" onclick="event.stopPropagation()">
        <button onclick="cerrarModal(null)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">✕</button>

        <img id="modalImgMain" src="" alt="" class="w-full h-48 object-contain bg-gray-50 dark:bg-gray-800 rounded-md mb-4">

        <p id="modalCateg" class="text-xs text-gray-400 uppercase"></p>
        <h3 id="modalNombre" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
        <p id="modalPrecio" class="text-2xl font-bold text-[#1b803a] mt-1"></p>
        <p class="text-xs text-gray-400 mt-2">SKU: <span id="modalSku"></span> · <span id="modalStockText"></span></p>
        <p id="modalDesc" class="text-sm text-gray-600 dark:text-gray-300 mt-3"></p>

        <button id="modalCartBtn" class="w-full mt-4 border border-gray-300 dark:border-gray-600 py-2 rounded-md text-sm font-medium">
            Agregar al carrito
        </button>
    </div>
</div>

<div id="cartToast" class="fixed bottom-6 right-6 z-[200] bg-gray-900 text-white text-sm px-4 py-2 rounded-md hidden">
    <span id="cartToastMsg">Producto agregado</span>
</div>

<script>
const CART_KEY = 'casatek_carrito';

function getToken()    { return localStorage.getItem('token') || null; }
function getCarrito()  { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCarrito(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); }

function actualizarBadge() {
    const total = getCarrito().reduce((acc, i) => acc + i.cantidad, 0);
    document.getElementById('cartBadge').textContent = total;
}

function mostrarToast(msg) {
    const t = document.getElementById('cartToast');
    document.getElementById('cartToastMsg').textContent = msg;
    t.classList.remove('hidden');
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.add('hidden'), 2000);
}

async function agregarAlCarrito(card) {
    const stock = parseInt(card.dataset.stock ?? '0');
    if (stock <= 0) { mostrarToast('Este producto no tiene stock disponible'); return; }

    const id       = String(card.dataset.id);
    const nombre   = card.dataset.nombre;
    const precio   = parseFloat(card.dataset.precio);
    const carrito  = getCarrito();
    const existe   = carrito.find(i => i.id === id);
    const yaEnCar  = existe ? existe.cantidad : 0;

    if (yaEnCar >= stock) {
        mostrarToast('Ya tienes el máximo disponible (' + stock + ' unid.)');
        return;
    }

    if (existe) {
        existe.cantidad++;
    } else {
        carrito.push({
            id, nombre,
            marca:       card.dataset.marca,
            precio,
            descripcion: card.dataset.descripcion,
            img:         card.dataset.img,
            cantidad:    1,
        });
    }
    saveCarrito(carrito);
    actualizarBadge();
    mostrarToast('"' + nombre + '" agregado al carrito');

    const token = getToken();
    if (!token) return;
    try {
        const res = await fetch('/api/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify({ product_id: parseInt(id), quantity: 1 }),
        });
        if (!res.ok) {
            const data = await res.json();
            const c    = getCarrito();
            const item = c.find(i => i.id === id);
            if (item) { item.cantidad--; if (item.cantidad <= 0) c.splice(c.indexOf(item), 1); saveCarrito(c); actualizarBadge(); }
            mostrarToast(data.message || 'Error al agregar al carrito');
        }
    } catch(e) { console.warn('No se pudo sincronizar:', e); }
}

document.getElementById('cartViewBtn').addEventListener('click', () => { window.location.href = '/carrito'; });

let productoModal = null;

function abrirModal(card) {
    const d = card.dataset;
    productoModal = {
        id: String(d.id), nombre: d.nombre, marca: d.marca, categoria: d.categoria,
        precio: parseFloat(d.precio), descripcion: d.descripcion, img: d.img,
        stock: parseInt(d.stock ?? '0'), sku: d.sku,
    };

    document.getElementById('modalImgMain').src = productoModal.img;
    document.getElementById('modalCateg').textContent = productoModal.marca + ' · ' + productoModal.categoria;
    document.getElementById('modalNombre').textContent = productoModal.nombre;
    document.getElementById('modalPrecio').textContent = productoModal.precio.toFixed(2) + ' Bs.';
    document.getElementById('modalDesc').textContent = productoModal.descripcion || 'Sin descripción.';
    document.getElementById('modalSku').textContent = productoModal.sku || '—';
    document.getElementById('modalStockText').textContent = productoModal.stock > 0 ? (productoModal.stock + ' en stock') : 'Sin stock';

    const btn = document.getElementById('modalCartBtn');
    if (productoModal.stock <= 0) {
        btn.disabled = true;
        btn.textContent = 'Sin stock';
        btn.classList.add('opacity-40');
    } else {
        btn.disabled = false;
        btn.textContent = 'Agregar al carrito';
        btn.classList.remove('opacity-40');
    }

    document.getElementById('productModal').classList.add('open');
}

function cerrarModal(event) {
    if (event === null || event.target === document.getElementById('productModal')) {
        document.getElementById('productModal').classList.remove('open');
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(null); });

document.getElementById('modalCartBtn').addEventListener('click', async () => {
    if (!productoModal) return;
    const fakeCard = { dataset: {
        id: productoModal.id, nombre: productoModal.nombre, marca: productoModal.marca,
        precio: productoModal.precio, descripcion: productoModal.descripcion,
        img: productoModal.img, stock: productoModal.stock,
    }};
    await agregarAlCarrito(fakeCard);
    if (productoModal.stock > 0) cerrarModal(null);
});

document.getElementById('searchInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('filtroForm').submit(); }
});

actualizarBadge();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\CASATEKv2\resources\views/productos.blade.php ENDPATH**/ ?>