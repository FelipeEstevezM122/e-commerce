<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
/* ↑ después del :root, agrega: */
body, html {
    background: #060d0a !important;
    margin: 0;
    padding: 0;
}
#productsPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:1400px; margin:0 auto; }

.section-label {
    font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase;
    color:var(--green); margin-bottom:6px;
}
.section-title {
    font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15;
}

.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin:28px 0; }
.stat-card {
    background:var(--card); border:1px solid var(--border); border-radius:18px;
    padding:22px 20px; transition:border-color .2s, transform .2s;
    position:relative; overflow:hidden;
}
.stat-card::before {
    content:''; position:absolute; top:0; left:0; right:0;
    height:2px; background:var(--accent-color, var(--green)); opacity:.7;
}
.stat-card:hover { border-color:var(--border-h); transform:translateY(-2px); }
.stat-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
.stat-label { font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
.stat-icon {
    width:38px; height:38px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:16px;
}
.stat-value {
    font-family:'Syne',sans-serif; font-size:36px; font-weight:800;
    color:#fff; line-height:1; margin-bottom:8px;
}
.stat-sub { font-size:11px; color:var(--muted); }

.search-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; margin-bottom:20px; }
.search-inner { display:flex; gap:12px; flex-wrap:wrap; }
.search-input-wrap { position:relative; flex:1; min-width:200px; }
.search-input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--green); font-size:13px; pointer-events:none; }
.s-input { width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:12px; padding:10px 14px 10px 40px; font-size:13px; font-family:'DM Sans',sans-serif; color:#fff; outline:none; transition:border-color .15s; }
.s-input:focus { border-color:var(--green); }
.s-input::placeholder { color:rgba(255,255,255,.2); }
select.s-input { padding-left:14px; }
.s-btn { background:var(--green-dark); color:#fff; border:none; border-radius:12px; padding:10px 20px; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; font-family:'DM Sans',sans-serif; }
.s-btn:hover { background:var(--green); }
.s-clear { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af; border-radius:12px; padding:10px 16px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; transition:all .15s; }
.s-clear:hover { color:#fff; }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; }
.panel-head { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(0,0,0,.3); }
th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
tbody tr:hover { background:rgba(255,255,255,.025); }
td { padding:14px 18px; font-size:13px; color:#d1d5db; }

.stock-badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
.stock-ok     { background:rgba(34,197,94,.12);  border:1px solid rgba(34,197,94,.3);  color:#4ade80; }
.stock-low    { background:rgba(250,204,21,.12); border:1px solid rgba(250,204,21,.3); color:#facc15; }
.stock-none   { background:rgba(239,68,68,.12);  border:1px solid rgba(239,68,68,.3);  color:#f87171; }

.act-btn { width:34px; height:34px; border-radius:10px; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-size:13px; transition:all .15s; text-decoration:none; }
.act-edit { background:rgba(96,165,250,.12); color:#60a5fa; border:1px solid rgba(96,165,250,.25); }
.act-edit:hover { background:rgba(96,165,250,.25); }
.act-del  { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); }
.act-del:hover  { background:rgba(239,68,68,.25); }

.pag { display:flex; align-items:center; justify-content:center; gap:6px; padding:16px; flex-wrap:wrap; border-top:1px solid var(--border); }
.pag-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:9px; font-size:12px; font-weight:700; border:1px solid rgba(255,255,255,.1); color:#9ca3af; background:rgba(255,255,255,.04); text-decoration:none; transition:all .15s; }
.pag-btn:hover { border-color:var(--green); color:var(--green); }
.pag-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.pag-btn.disabled { opacity:.3; pointer-events:none; }

.empty-state { text-align:center; padding:56px 20px; color:var(--muted); }
.empty-state i { font-size:40px; opacity:.3; display:block; margin-bottom:12px; }

#adminToast { opacity:0; transform:translateY(8px); transition:opacity .2s, transform .2s; pointer-events:none; }
#adminToast.show { opacity:1; transform:translateY(0); }

.fade-up { animation: fadeUp .4s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.delay-1 { animation-delay:.07s; } .delay-2 { animation-delay:.14s; }
.delay-3 { animation-delay:.21s; } .delay-4 { animation-delay:.28s; }

@media(max-width:1100px) { .stat-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px) { #main { padding:20px 16px 40px; } .stat-grid { grid-template-columns:1fr 1fr; } }
</style>

<div id="productsPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    <?php echo $__env->make('partials.header-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main id="main">

        
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;flex-wrap:wrap;gap:12px" class="fade-up">
            <div>
                <p class="section-label"><i class="fa-solid fa-box mr-1"></i> Catálogo</p>
                <h1 class="section-title">Panel de <span style="color:var(--green)">Productos</span></h1>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Administración general del catálogo Casatek</p>
            </div>
            <a href="<?php echo e(route('admin.products.create')); ?>"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--green);color:#fff;font-weight:700;font-size:13px;padding:11px 22px;border-radius:12px;text-decoration:none;transition:background .15s;margin-top:8px"
               onmouseover="this.style.background='var(--green-dark)'" onmouseout="this.style.background='var(--green)'">
                <i class="fa-solid fa-plus"></i> Agregar Producto
            </a>
        </div>

        
        <?php if(session('success')): ?>
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-circle-check"></i><?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p><i class="fa-solid fa-triangle-exclamation mr-1"></i><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <div class="stat-grid">
            <div class="stat-card fade-up delay-1" style="--accent-color:var(--green)">
                <div class="stat-top">
                    <span class="stat-label">Productos</span>
                    <div class="stat-icon" style="background:rgba(34,197,94,.15)">
                        <i class="fa-solid fa-box" style="color:#22C55E"></i>
                    </div>
                </div>
                <p class="stat-value"><?php echo e($totalProducts); ?></p>
                <div class="stat-sub">en catálogo activo</div>
            </div>
            <div class="stat-card fade-up delay-2" style="--accent-color:#3b82f6">
                <div class="stat-top">
                    <span class="stat-label">Marcas</span>
                    <div class="stat-icon" style="background:rgba(59,130,246,.15)">
                        <i class="fa-solid fa-tag" style="color:#60a5fa"></i>
                    </div>
                </div>
                <p class="stat-value"><?php echo e($totalBrands); ?></p>
                <div class="stat-sub">marcas registradas</div>
            </div>
            <div class="stat-card fade-up delay-3" style="--accent-color:#facc15">
                <div class="stat-top">
                    <span class="stat-label">Stock Bajo</span>
                    <div class="stat-icon" style="background:rgba(250,204,21,.15)">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#facc15"></i>
                    </div>
                </div>
                <p class="stat-value"><?php echo e($lowStock); ?></p>
                <div class="stat-sub">productos con poco stock</div>
            </div>
            <div class="stat-card fade-up delay-4" style="--accent-color:#ef4444">
                <div class="stat-top">
                    <span class="stat-label">Sin Stock</span>
                    <div class="stat-icon" style="background:rgba(239,68,68,.15)">
                        <i class="fa-solid fa-ban" style="color:#f87171"></i>
                    </div>
                </div>
                <p class="stat-value"><?php echo e($noStock); ?></p>
                <div class="stat-sub">productos agotados</div>
            </div>
        </div>

        
        <div class="search-wrap">
            <form method="GET" action="<?php echo e(route('admin.products.index')); ?>">
                <div class="search-inner">
                    <div class="search-input-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="Buscar producto..." class="s-input">
                    </div>
                    <select name="category_id" class="s-input" style="width:200px;padding-left:14px">
                        <option value="">Todas las categorías</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="s-btn"><i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar</button>
                    <?php if(request('search') || request('category_id')): ?>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="s-clear"><i class="fa-solid fa-xmark"></i> Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        
        <div class="panel">
            <div class="panel-head">
                <h2><i class="fa-solid fa-table-list" style="color:var(--green)"></i> Gestión de Productos</h2>
                <span style="font-size:11px;color:var(--muted)"><?php echo e($products->total()); ?> productos en total</span>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th style="text-align:center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($product->image1 ?? 'https://via.placeholder.com/56'); ?>"
                                     class="w-14 h-14 rounded-xl object-cover"
                                     style="border:1px solid rgba(255,255,255,.08)"
                                     onerror="this.src='https://via.placeholder.com/56'">
                            </td>
                            <td>
                                <p style="font-weight:700;color:#fff;font-size:13px"><?php echo e($product->name); ?></p>
                                <p style="font-size:11px;color:var(--muted);margin-top:2px">SKU: <?php echo e($product->sku); ?></p>
                            </td>
                            <td style="font-weight:600;color:#e5e7eb"><?php echo e($product->brand->name ?? '—'); ?></td>
                            <td>
                                <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--green)">
                                    <?php echo e($product->category->name ?? '—'); ?>

                                </span>
                            </td>
                            <td style="color:var(--green);font-weight:800">Bs. <?php echo e(number_format($product->base_price, 2)); ?></td>
                            <td style="font-weight:800;color:<?php echo e($product->stock <= 10 ? '#f87171' : '#fff'); ?>">
                                <?php echo e($product->stock); ?>

                            </td>
                            <td>
                                <?php if($product->stock === 0): ?>
                                    <span class="stock-badge stock-none">Sin Stock</span>
                                <?php elseif($product->stock <= 10): ?>
                                    <span class="stock-badge stock-low">Stock Bajo</span>
                                <?php else: ?>
                                    <span class="stock-badge stock-ok">Activo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;justify-content:center;gap:6px">
                                    <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="act-btn act-edit" title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" method="POST"
                                          onsubmit="return confirm('¿Eliminar <?php echo e(addslashes($product->name)); ?>?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="act-btn act-del" title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-box-open"></i>
                                    <p style="font-size:14px;font-weight:600">No se encontraron productos</p>
                                    <a href="<?php echo e(route('admin.products.index')); ?>" style="color:var(--green);font-size:13px;font-weight:700;text-decoration:none;display:inline-block;margin-top:8px">
                                        Ver todos los productos
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($products->hasPages()): ?>
            <div class="pag">
                <?php if($products->onFirstPage()): ?>
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                <?php else: ?>
                    <a href="<?php echo e($products->withQueryString()->previousPageUrl()); ?>" class="pag-btn"><i class="fa-solid fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php $__currentLoopData = $products->withQueryString()->getUrlRange(1, $products->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $products->currentPage()): ?>
                        <span class="pag-btn active"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="pag-btn"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($products->hasMorePages()): ?>
                    <a href="<?php echo e($products->withQueryString()->nextPageUrl()); ?>" class="pag-btn"><i class="fa-solid fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="pag-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
            <p style="text-align:center;font-size:11px;color:var(--muted);padding-bottom:14px">
                Página <?php echo e($products->currentPage()); ?> de <?php echo e($products->lastPage()); ?>

            </p>
            <?php endif; ?>
        </div>

    </main>
</div>


<div id="adminToast"
     class="fixed bottom-6 right-6 z-[200]"
     style="background:#111f16;border:1px solid rgba(34,197,94,.3);color:#fff;font-size:13px;font-weight:600;padding:12px 18px;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,.5);display:flex;align-items:center;gap:8px;">
    <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
    <span id="adminToastMsg">Acción completada</span>
</div>

<script>
<?php if(session('success')): ?>
    (function(){
        const t = document.getElementById('adminToast');
        document.getElementById('adminToastMsg').textContent = '<?php echo e(session('success')); ?>';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    })();
<?php endif; ?>
</script><?php /**PATH C:\Users\Joker\Desktop\CASATE_laravel_V9\CASATEK\resources\views/admin/create-product.blade.php ENDPATH**/ ?>