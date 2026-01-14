<?php
/**
 * Vista: Formulario de Película (Admin) - UX Premium
 * Con dropzone drag & drop, toggle switches, y gestión de imágenes
 */
$isEdit = isset($movie);
$pageTitle = $isEdit ? 'Editar Película' : 'Nueva Película';

// Extraer solo el nombre del video (sin movies/ ni extensión)
$videoName = '';
if ($isEdit && !empty($movie['video_path'])) {
    $videoName = $movie['video_path'];
    if (str_starts_with($videoName, 'movies/')) {
        $videoName = substr($videoName, 7);
    }
    $videoName = pathinfo($videoName, PATHINFO_FILENAME);
}

ob_start();
?>

<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
}
/* Toggle Switch personalizado */
.toggle-switch {
    position: relative;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #3f3f46;
    border-radius: 999px;
    transition: 0.3s;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
}
.toggle-switch input:checked + .toggle-slider {
    background: #ef4444;
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
}
.toggle-switch.purple input:checked + .toggle-slider {
    background: #a855f7;
}
/* Dropzone mejorado */
.image-dropzone {
    position: relative;
    border: 2px dashed #3f3f46;
    border-radius: 1rem;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all 0.2s;
    cursor: pointer;
    overflow: hidden;
}
.image-dropzone:hover, .image-dropzone.dragover {
    border-color: #ef4444;
    background: rgba(239,68,68,0.05);
}
.image-dropzone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}
.image-dropzone .preview-container {
    position: absolute;
    inset: 0;
    display: none;
}
.image-dropzone .preview-container.active {
    display: block;
}
.image-dropzone .preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-dropzone .preview-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0.75rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}
.image-dropzone .placeholder {
    padding: 2rem;
}
</style>

<div class="max-w-5xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold"><?= $pageTitle ?></h1>
            <?php if ($isEdit): ?>
            <p class="text-gray-400 mt-1"><?= e($movie['title']) ?> • <?= $movie['year'] ?? 'Sin año' ?></p>
            <?php endif; ?>
        </div>
        <a href="<?= url('admin/movies') ?>" class="text-gray-400 hover:text-white transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>
    </div>
    
    <!-- Formulario -->
    <form action="<?= url('admin/movies/' . ($isEdit ? 'update/' . $movie['id'] : 'store')) ?>" method="POST" enctype="multipart/form-data" class="bg-jf-card rounded-2xl p-6 md:p-8">
        <?= csrfField() ?>
        
        <!-- Header del formulario -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-white/10">
            <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Información de la Película</h2>
                <p class="text-gray-400 text-sm">Completa los datos para crear o editar la película</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Título de la Película *</label>
                <input type="text" name="title" value="<?= e($movie['title'] ?? '') ?>" required
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 text-lg"
                       placeholder="Ingresa el título de la película">
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Sinopsis</label>
                <textarea name="description" rows="3" placeholder="Breve descripción de la película..."
                          class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 resize-none"><?= e($movie['description'] ?? '') ?></textarea>
            </div>
            
            <!-- Año y Duración -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Año de Estreno</label>
                <input type="number" name="year" value="<?= e($movie['year'] ?? date('Y')) ?>" min="1900" max="2030"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Duración (minutos)</label>
                <input type="number" name="duration" value="<?= e($movie['duration'] ?? '') ?>" min="1" placeholder="120"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500">
            </div>
            
            <!-- Nombre del Video -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Nombre del Archivo de Video *</label>
                <div class="relative">
                    <input type="text" name="video_name" value="<?= e($videoName) ?>" required 
                           placeholder="nombre-de-la-pelicula"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl pl-4 pr-28 py-3 focus:outline-none focus:border-red-500">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">.mp4 / .mkv</span>
                </div>
                <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Ubicación: <code class="bg-zinc-800 px-2 py-0.5 rounded">public/media/movies/[nombre].[ext]</code></span>
                </div>
            </div>
            
            <!-- Imágenes con Dropzone -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-3">Imágenes</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Poster -->
                    <div class="image-dropzone" id="posterDropzone">
                        <div class="preview-container <?= ($isEdit && !empty($movie['poster'])) ? 'active' : '' ?>" id="posterPreviewContainer">
                            <img src="<?= ($isEdit && !empty($movie['poster'])) ? posterUrl($movie['poster']) : '' ?>" alt="" class="preview-img" id="posterPreviewImg">
                            <div class="preview-actions">
                                <button type="button" onclick="changeImage('poster')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Cambiar
                                </button>
                                <button type="button" onclick="removeImage('poster')" class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        <div class="placeholder" id="posterPlaceholder">
                            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium text-gray-300">Poster</p>
                            <p class="text-xs text-gray-500">Arrastra o haz clic para seleccionar</p>
                        </div>
                        <input type="file" name="poster" accept="image/*" id="posterInput" onchange="previewImage(this, 'poster')">
                    </div>
                    
                    <!-- Backdrop -->
                    <div class="image-dropzone" id="backdropDropzone">
                        <div class="preview-container <?= ($isEdit && !empty($movie['backdrop'])) ? 'active' : '' ?>" id="backdropPreviewContainer">
                            <img src="<?= ($isEdit && !empty($movie['backdrop'])) ? posterUrl($movie['backdrop']) : '' ?>" alt="" class="preview-img" id="backdropPreviewImg">
                            <div class="preview-actions">
                                <button type="button" onclick="changeImage('backdrop')" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Cambiar
                                </button>
                                <button type="button" onclick="removeImage('backdrop')" class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        <div class="placeholder" id="backdropPlaceholder">
                            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium text-gray-300">Backdrop (Fondo)</p>
                            <p class="text-xs text-gray-500">Imagen horizontal para fondo</p>
                        </div>
                        <input type="file" name="backdrop" accept="image/*" id="backdropInput" onchange="previewImage(this, 'backdrop')">
                    </div>
                </div>
            </div>
            
            <!-- Categorías -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-3">Géneros / Categorías</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($categories as $cat): ?>
                    <label class="inline-flex items-center gap-2 bg-zinc-800 px-4 py-2.5 rounded-xl cursor-pointer hover:bg-zinc-700 transition border border-transparent has-[:checked]:border-red-500 has-[:checked]:bg-red-500/10">
                        <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" 
                               <?= (isset($selectedCategories) && in_array($cat['id'], $selectedCategories)) ? 'checked' : '' ?>
                               class="hidden">
                        <span class="text-sm"><?= e($cat['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Opciones con Toggle Switches -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-3">Opciones</label>
                <div class="flex flex-wrap gap-6">
                    <!-- Destacar en Inicio -->
                    <label class="flex items-center gap-4 bg-zinc-800/50 px-5 py-4 rounded-xl cursor-pointer hover:bg-zinc-800 transition">
                        <div class="toggle-switch">
                            <input type="checkbox" name="is_featured" value="1" <?= ($movie['is_featured'] ?? false) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </div>
                        <div>
                            <span class="font-medium block">Destacar en Inicio</span>
                            <span class="text-xs text-gray-500">Aparecerá en el carrusel principal</span>
                        </div>
                    </label>
                    
                    <!-- Caja Fuerte -->
                    <label class="flex items-center gap-4 bg-zinc-800/50 px-5 py-4 rounded-xl cursor-pointer hover:bg-zinc-800 transition">
                        <div class="toggle-switch purple">
                            <input type="checkbox" name="is_vault" value="1" <?= ($movie['is_vault'] ?? false) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </div>
                        <div>
                            <span class="font-medium block">Caja Fuerte</span>
                            <span class="text-xs text-gray-500">Requiere código para acceder</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <?= $isEdit ? 'Guardar Cambios' : 'Crear Película' ?>
            </button>
            <a href="<?= url('admin/movies') ?>" class="text-gray-400 hover:text-white transition">Cancelar</a>
        </div>
    </form>
</div>

<script>
// Preview de imagen
function previewImage(input, type) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById(type + 'PreviewImg').src = e.target.result;
            document.getElementById(type + 'PreviewContainer').classList.add('active');
            document.getElementById(type + 'Placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Cambiar imagen (trigger input)
function changeImage(type) {
    document.getElementById(type + 'Input').click();
}

// Eliminar imagen
function removeImage(type) {
    document.getElementById(type + 'Input').value = '';
    document.getElementById(type + 'PreviewContainer').classList.remove('active');
    document.getElementById(type + 'PreviewImg').src = '';
    document.getElementById(type + 'Placeholder').style.display = 'block';
}

// Drag and drop
['poster', 'backdrop'].forEach(type => {
    const dropzone = document.getElementById(type + 'Dropzone');
    if (!dropzone) return;
    
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const input = document.getElementById(type + 'Input');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            previewImage(input, type);
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
