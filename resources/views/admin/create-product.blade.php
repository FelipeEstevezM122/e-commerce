
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
#createProductPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:860px; margin:0 auto; }

.form-card {
    background:var(--card); border:1px solid var(--border); border-radius:20px; padding:32px;
}
.form-label { display:block; font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.form-input {
    width:100%; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);
    border-radius:12px; padding:10px 14px; font-size:13px; font-family:'DM Sans',sans-serif;
    color:#fff; outline:none; transition:border-color .15s;
}
.form-input:focus { border-color:var(--green); }
.form-input::placeholder { color:rgba(255,255,255,.25); }
select.form-input option { background:#1f2937; }
textarea.form-input { resize:vertical; }

.btn-primary {
    flex:1; background:var(--green); color:#fff; border:none; border-radius:12px;
    padding:12px 20px; font-size:13px; font-weight:700; font-family:'DM Sans',sans-serif;
    cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px;
}
.btn-primary:hover { background:var(--green-dark); }
.btn-cancel {
    flex:1; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#9ca3af;
    border-radius:12px; padding:12px 20px; font-size:13px; font-weight:700;
    text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px;
    transition:all .15s;
}
.btn-cancel:hover { background:rgba(255,255,255,.08); color:#fff; }

.file-input {
    width:100%; background:rgba(255,255,255,.03); border:1px dashed rgba(255,255,255,.15);
    border-radius:12px; padding:10px 14px; font-size:13px; color:#9ca3af; cursor:pointer;
}
.file-input:hover { border-color:var(--green); }

@media(max-width:768px) { #main { padding:20px 16px 40px; } .form-card { padding:20px; } }
</style>

<div id="createProductPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        <div style="margin-bottom:24px">
            <p style="font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--green);margin-bottom:4px">
                <i class="fa-solid fa-box-open mr-1"></i> Productos
            </p>
            <h1 style="font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#fff">Añadir Nuevo Producto</h1>
            <p style="font-size:12px;color:var(--muted);margin-top:4px">Las imágenes se suben a Cloudinary automáticamente</p>
        </div>

        @if(session('success'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px">
            @foreach($errors->all() as $error)
                <p><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom:18px">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ej. Cámara Robótica Exterior" class="form-input">
                </div>

                <div style="margin-bottom:18px">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Descripción del producto..." class="form-input">{{ old('description') }}</textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
                    <div>
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Ej. CAM-001" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Precio base (Bs.) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" placeholder="0.00" step="0.01" min="0" class="form-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
                    <div>
                        <label class="form-label">Stock *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Días de garantía</label>
                        <input type="number" name="warranty_days" value="{{ old('warranty_days', 0) }}" min="0" class="form-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
                    <div>
                        <label class="form-label">Marca</label>
                        <select name="brand_id" class="form-input">
                            <option value="">Sin marca</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Categoría</label>
                        <select name="category_id" class="form-input">
                            <option value="">Sin categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:24px">
                    <label class="form-label">Imágenes (máx. 4)</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        @foreach([1,2,3,4] as $i)
                        <div>
                            <p style="font-size:11px;color:var(--muted);margin-bottom:4px">Imagen {{ $i }}</p>
                            <input type="file" name="image{{ $i }}" accept="image/jpeg,image/png,image/webp" class="file-input">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div style="display:flex;gap:12px">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Guardar Producto
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </main>
</div>
