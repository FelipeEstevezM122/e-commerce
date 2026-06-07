<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap');
:root {
    --green:#22C55E; --green-dark:#15803d;
    --bg:#060d0a; --card:#111f16; --border:rgba(34,197,94,.12); --border-h:rgba(34,197,94,.35);
    --text:#f3f4f6; --muted:#6b7280;
}
body, html { background:#060d0a !important; margin:0; padding:0; }
#createPage { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; color:var(--text); }
#main { padding:32px 28px 48px; max-width:960px; margin:0 auto; }

.section-label { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--green); margin-bottom:6px; }
.section-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#fff; line-height:1.15; }

.back-btn {
    display:inline-flex; align-items:center; gap:6px;
    color:var(--muted); font-size:13px; font-weight:600; text-decoration:none;
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
    padding:8px 14px; border-radius:10px; transition:all .15s;
}
.back-btn:hover { color:#fff; border-color:rgba(255,255,255,.18); }

.panel { background:var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden; margin-top:24px; }
.panel-head { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; }
.panel-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#fff; }
.panel-body { padding:28px 24px; }

.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.form-grid.full { grid-template-columns:1fr; }
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-label { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
.form-label span { color:#f87171; margin-left:2px; }

.f-input, .f-select, .f-textarea {
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);
    border-radius:12px; padding:11px 14px; font-size:13px;
    font-family:'DM Sans',sans-serif; color:#fff; outline:none;
    transition:border-color .15s, background .15s; width:100%; box-sizing:border-box;
}
.f-input:focus, .f-select:focus, .f-textarea:focus {
    border-color:var(--green); background:rgba(34,197,94,.04);
}
.f-input::placeholder, .f-textarea::placeholder { color:rgba(255,255,255,.2); }
.f-select option { background:#111f16; color:#fff; }
.f-textarea { resize:vertical; min-height:100px; }

.f-input.has-error { border-color:rgba(239,68,68,.5); }

.err-msg { font-size:11px; color:#f87171; margin-top:2px; display:flex; align-items:center; gap:4px; }

/* Sección de imágenes */
.img-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
.img-upload {
    position:relative; border:2px dashed rgba(255,255,255,.1); border-radius:14px;
    aspect-ratio:1; display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:8px; cursor:pointer; transition:all .2s;
    overflow:hidden;
}
.img-upload:hover { border-color:var(--green); background:rgba(34,197,94,.04); }
.img-upload input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }
.img-upload i { font-size:22px; color:rgba(255,255,255,.2); pointer-events:none; transition:color .2s; }
.img-upload:hover i { color:var(--green); }
.img-upload p { font-size:10px; font-weight:700; color:rgba(255,255,255,.25); text-transform:uppercase; letter-spacing:.06em; pointer-events:none; transition:color .2s; }
.img-upload:hover p { color:var(--green); }
.img-preview { position:absolute; inset:0; object-fit:cover; border-radius:12px; display:none; }
.img-upload .img-label { font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.15); position:absolute; bottom:8px; }

/* Separador de sección */
.section-sep { border:none; border-top:1px solid var(--border); margin:28px 0; }

/* Botones de acción */
.form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:32px; flex-wrap:wrap; }
.btn-cancel {
    background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1);
    color:#9ca3af; border-radius:12px; padding:11px 24px; font-size:13px;
    font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif;
    text-decoration:none; display:inline-flex; align-items:center; gap:8px;
    transition:all .15s;
}
.btn-cancel:hover { color:#fff; background:rgba(255,255,255,.08); }
.btn-submit {
    background:var(--green); color:#fff; border:none; border-radius:12px;
    padding:11px 28px; font-size:13px; font-weight:700; cursor:pointer;
    font-family:'DM Sans',sans-serif; display:inline-flex; align-items:center;
    gap:8px; transition:background .15s;
}
.btn-submit:hover { background:var(--green-dark); }
.btn-submit:disabled { opacity:.5; cursor:not-allowed; }

/* Spinner */
.spinner { display:none; width:14px; height:14px; border:2px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin .6s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }

.fade-up { animation:fadeUp .4s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.delay-1 { animation-delay:.07s; } .delay-2 { animation-delay:.14s; }

/* Alerta de errores */
.alert-err { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.25); color:#f87171; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:13px; }
.alert-err ul { margin:8px 0 0 0; padding-left:18px; }
.alert-err li { margin-top:4px; }

/* Tooltip del SKU */
.hint { font-size:11px; color:var(--muted); margin-top:4px; }

@media(max-width:700px) {
    .form-grid { grid-template-columns:1fr; }
    .img-grid { grid-template-columns:repeat(2,1fr); }
    #main { padding:20px 16px 40px; }
}
</style>

<div id="createPage" class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-6">

    @include('partials.header-admin')

    <main id="main">

        {{-- ENCABEZADO --}}
        <div class="fade-up" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:4px">
            <div>
                <p class="section-label"><i class="fa-solid fa-box mr-1"></i> Catálogo</p>
                <h1 class="section-title">Agregar <span style="color:var(--green)">Producto</span></h1>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Completa los campos para registrar un nuevo producto en el catálogo Casatek</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="back-btn" style="margin-top:8px">
                <i class="fa-solid fa-arrow-left"></i> Volver al panel
            </a>
        </div>

        {{-- ERRORES DE VALIDACIÓN --}}
        @if($errors->any())
        <div class="alert-err fade-up">
            <div style="display:flex;align-items:center;gap:8px;font-weight:700">
                <i class="fa-solid fa-triangle-exclamation"></i> Por favor corrige los siguientes errores:
            </div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- FORMULARIO --}}
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            {{-- BLOQUE 1: INFORMACIÓN BÁSICA --}}
            <div class="panel fade-up delay-1">
                <div class="panel-head">
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(34,197,94,.15);display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-circle-info" style="color:var(--green);font-size:14px"></i>
                    </div>
                    <h2>Información básica</h2>
                </div>
                <div class="panel-body">

                    <div class="form-grid" style="margin-bottom:20px">
                        {{-- Nombre --}}
                        <div class="form-group" style="grid-column:1/-1">
                            <label class="form-label">Nombre del producto <span>*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   placeholder="Ej: Televisor Samsung 55&quot; 4K QLED"
                                   class="f-input {{ $errors->has('name') ? 'has-error' : '' }}">
                            @error('name')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- SKU --}}
                        <div class="form-group">
                            <label class="form-label">SKU <span>*</span></label>
                            <input type="text" name="sku" value="{{ old('sku') }}"
                                   placeholder="Ej: SAM-TV55-4K"
                                   class="f-input {{ $errors->has('sku') ? 'has-error' : '' }}"
                                   style="font-family:monospace;letter-spacing:.05em">
                            <p class="hint"><i class="fa-solid fa-circle-info" style="font-size:10px"></i> Código único del producto. No puede repetirse.</p>
                            @error('sku')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Días de garantía --}}
                        <div class="form-group">
                            <label class="form-label">Días de garantía</label>
                            <input type="number" name="warranty_days" value="{{ old('warranty_days', 365) }}"
                                   min="0" placeholder="365"
                                   class="f-input {{ $errors->has('warranty_days') ? 'has-error' : '' }}">
                            @error('warranty_days')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" placeholder="Describe las características del producto..."
                                  class="f-textarea {{ $errors->has('description') ? 'has-error' : '' }}">{{ old('description') }}</textarea>
                        @error('description')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            {{-- BLOQUE 2: PRECIO, STOCK, MARCA Y CATEGORÍA --}}
            <div class="panel fade-up delay-2" style="margin-top:16px">
                <div class="panel-head">
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(34,197,94,.15);display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-tag" style="color:var(--green);font-size:14px"></i>
                    </div>
                    <h2>Precio, stock y clasificación</h2>
                </div>
                <div class="panel-body">

                    <div class="form-grid">
                        {{-- Precio base --}}
                        <div class="form-group">
                            <label class="form-label">Precio base (Bs.) <span>*</span></label>
                            <div style="position:relative">
                                <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--green);font-size:13px;font-weight:700">Bs.</span>
                                <input type="number" name="base_price" value="{{ old('base_price') }}"
                                       min="0" step="0.01" placeholder="0.00"
                                       class="f-input {{ $errors->has('base_price') ? 'has-error' : '' }}"
                                       style="padding-left:42px">
                            </div>
                            @error('base_price')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Stock --}}
                        <div class="form-group">
                            <label class="form-label">Stock <span>*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   min="0" placeholder="0"
                                   class="f-input {{ $errors->has('stock') ? 'has-error' : '' }}">
                            @error('stock')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Marca --}}
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <select name="brand_id" class="f-select {{ $errors->has('brand_id') ? 'has-error' : '' }}">
                                <option value="">— Sin marca —</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Categoría --}}
                        <div class="form-group">
                            <label class="form-label">Categoría</label>
                            <select name="category_id" class="f-select {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                <option value="">— Sin categoría —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="err-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- BLOQUE 3: IMÁGENES --}}
            <div class="panel" style="margin-top:16px">
                <div class="panel-head">
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(34,197,94,.15);display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-images" style="color:var(--green);font-size:14px"></i>
                    </div>
                    <h2>Imágenes del producto</h2>
                </div>
                <div class="panel-body">
                    <p style="font-size:12px;color:var(--muted);margin-bottom:18px">
                        Sube hasta 4 imágenes. Formatos: JPG, PNG, WEBP. Máximo 4 MB por imagen.
                        La primera imagen será la principal.
                    </p>
                    <div class="img-grid">
                        @foreach(['image1','image2','image3','image4'] as $i => $field)
                        <div class="form-group">
                            <label class="img-upload" id="label-{{ $field }}" for="{{ $field }}">
                                <input type="file" name="{{ $field }}" id="{{ $field }}"
                                       accept="image/jpeg,image/png,image/jpg,image/webp"
                                       onchange="previewImg(this, '{{ $field }}')">
                                <img class="img-preview" id="preview-{{ $field }}" alt="preview">
                                <i class="fa-solid fa-cloud-arrow-up" id="icon-{{ $field }}"></i>
                                <p id="text-{{ $field }}">{{ $i === 0 ? 'Principal' : 'Imagen '.($i+1) }}</p>
                                <span class="img-label">{{ $i === 0 ? '★ Principal' : '#'.($i+1) }}</span>
                            </label>
                            @error($field)<p class="err-msg" style="font-size:10px"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="spinner" id="spinner"></span>
                    <i class="fa-solid fa-floppy-disk" id="submitIcon"></i>
                    <span id="submitText">Guardar producto</span>
                </button>
            </div>

        </form>

    </main>
</div>

<script>
// Preview de imágenes
function previewImg(input, field) {
    const preview = document.getElementById('preview-' + field);
    const icon    = document.getElementById('icon-' + field);
    const text    = document.getElementById('text-' + field);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            icon.style.display    = 'none';
            text.style.display    = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Spinner al enviar
document.getElementById('productForm').addEventListener('submit', function() {
    const btn    = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');
    const icon   = document.getElementById('submitIcon');
    const txt    = document.getElementById('submitText');

    btn.disabled      = true;
    spinner.style.display = 'block';
    icon.style.display    = 'none';
    txt.textContent       = 'Guardando...';
});
</script>