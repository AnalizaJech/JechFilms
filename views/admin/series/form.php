<?php
/**
 * Vista: Formulario de Serie (Admin) - UX Mejorada
 * - Separación clara entre info de serie y episodios
 * - Formulario de episodios más intuitivo
 * - Año fin muestra "Presente" si está vacío
 */
$isEdit = isset($series);
$pageTitle = $isEdit ? 'Editar Serie' : 'Nueva Serie';

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
    background: #a855f7;
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
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
    border-color: #a855f7;
    background: rgba(168,85,247,0.05);
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
            <p class="text-gray-400 mt-1"><?= e($series['title']) ?> • <?= $series['year_start'] ?>-<?= $series['year_end'] ?: 'Presente' ?></p>
            <?php endif; ?>
        </div>
        <a href="<?= url('admin/series') ?>" class="text-gray-400 hover:text-white transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a Series
        </a>
    </div>
    
    <!-- Tabs para edición (muestran/ocultan secciones) -->
    <?php if ($isEdit): ?>
    <div class="flex items-center gap-2 sm:gap-4 mb-6 border-b border-white/10 pb-4">
        <span class="text-sm text-gray-400 hidden sm:block">Secciones:</span>
        <button type="button" onclick="switchTab('info')" id="tabInfo" class="px-4 py-2 rounded-lg bg-purple-500/20 text-purple-400 text-sm font-medium transition">Información</button>
        <button type="button" onclick="switchTab('episodes')" id="tabEpisodes" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-medium transition">Episodios</button>
    </div>
    <?php endif; ?>
    
    <!-- Formulario de Información de Serie -->
    <form id="sectionInfo" action="<?= url('admin/series/' . ($isEdit ? 'update/' . $series['id'] : 'store')) ?>" method="POST" enctype="multipart/form-data" class="bg-jf-card rounded-2xl p-6 md:p-8 mb-8">
        <?= csrfField() ?>
        
        <!-- Header del formulario -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-white/10">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Información de la Serie</h2>
                <p class="text-gray-400 text-sm">Datos generales, imágenes y categorías</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Título de la Serie *</label>
                <input type="text" name="title" value="<?= e($series['title'] ?? '') ?>" required
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 text-lg">
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Sinopsis</label>
                <textarea name="description" rows="3" placeholder="Breve descripción de la serie..."
                          class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 resize-none overflow-y-auto"><?= e($series['description'] ?? '') ?></textarea>
            </div>
            
            <!-- Años -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-3">Período de Emisión</label>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="number" name="year_start" value="<?= e($series['year_start'] ?? date('Y')) ?>" min="1900" max="2030" placeholder="Año inicio"
                               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                        <span class="text-xs text-gray-500 mt-1 block">Año de inicio</span>
                    </div>
                    <span class="text-gray-500 text-2xl">—</span>
                    <div class="flex-1">
                        <input type="number" name="year_end" value="<?= e($series['year_end'] ?? '') ?>" min="1900" max="2030" placeholder="En emisión"
                               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                        <span class="text-xs text-gray-500 mt-1 block">Dejar vacío si aún está en emisión</span>
                    </div>
                </div>
            </div>
            
            <!-- Total Temporadas -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Total de Temporadas</label>
                <input type="number" name="total_seasons" value="<?= e($series['total_seasons'] ?? 1) ?>" min="1"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                <span class="text-xs text-gray-500 mt-1 block">Se actualiza automáticamente al agregar episodios</span>
            </div>
            
            <!-- Espacio vacío para alinear -->
            <div></div>
            
            <!-- Imágenes con Dropzone -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-3">Imágenes</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Poster -->
                    <div class="image-dropzone" id="posterDropzone">
                        <div class="preview-container <?= ($isEdit && !empty($series['poster'])) ? 'active' : '' ?>" id="posterPreviewContainer">
                            <img src="<?= ($isEdit && !empty($series['poster'])) ? posterUrl($series['poster']) : '' ?>" alt="" class="preview-img" id="posterPreviewImg">
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
                        <div class="preview-container <?= ($isEdit && !empty($series['backdrop'])) ? 'active' : '' ?>" id="backdropPreviewContainer">
                            <img src="<?= ($isEdit && !empty($series['backdrop'])) ? posterUrl($series['backdrop']) : '' ?>" alt="" class="preview-img" id="backdropPreviewImg">
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
                    <label class="inline-flex items-center gap-2 bg-zinc-800 px-4 py-2.5 rounded-xl cursor-pointer hover:bg-zinc-700 transition border border-transparent has-[:checked]:border-purple-500 has-[:checked]:bg-purple-500/10">
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
                            <input type="checkbox" name="is_featured" value="1" <?= ($series['is_featured'] ?? false) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </div>
                        <div>
                            <span class="font-medium block">Destacar en Inicio</span>
                            <span class="text-xs text-gray-500">Aparecerá en el carrusel principal</span>
                        </div>
                    </label>
                    
                    <!-- Caja Fuerte -->
                    <label class="flex items-center gap-4 bg-zinc-800/50 px-5 py-4 rounded-xl cursor-pointer hover:bg-zinc-800 transition">
                        <div class="toggle-switch">
                            <input type="checkbox" name="is_vault" value="1" <?= ($series['is_vault'] ?? false) ? 'checked' : '' ?>>
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
            <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-xl font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <?= $isEdit ? 'Guardar Cambios' : 'Crear Serie' ?>
            </button>
            <a href="<?= url('admin/series') ?>" class="text-gray-400 hover:text-white transition">Cancelar</a>
        </div>
    </form>
    
    <!-- Gestión de Episodios (solo en edición) - Oculto por defecto -->
    <?php if ($isEdit): ?>
    <div id="sectionEpisodes" class="bg-[#121212] border border-white/10 rounded-2xl p-6 md:p-8 hidden mt-6 text-gray-200">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-white/10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Episodios</h2>
                    <p class="text-gray-400 text-sm">Gestiona los episodios por temporada</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">
                <?php 
                $totalEps = 0;
                foreach ($seasons ?? [] as $eps) $totalEps += count($eps);
                echo $totalEps . ' episodios en ' . count($seasons ?? []) . ' temporadas';
                ?>
            </span>
        </div>
        
        <!-- Lista de Episodios por Temporada -->
        <?php if (!empty($seasons)): ?>
        <div class="space-y-6 mb-8">
            <?php foreach ($seasons as $seasonNum => $episodes): ?>
            <div class="bg-zinc-800/30 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-zinc-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold text-sm">
                            <?= $seasonNum ?>
                        </div>
                        <span class="font-semibold">Temporada <?= $seasonNum ?></span>
                    </div>
                    <span class="text-sm text-gray-500"><?= count($episodes) ?> episodios</span>
                </div>
                <div class="divide-y divide-white/5">
                    <?php foreach ($episodes as $ep): ?>
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-white/5 transition">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 font-medium text-sm">
                                <?= $ep['episode_number'] ?>
                            </span>
                            <div>
                                <span class="font-medium"><?= e($ep['title']) ?></span>
                                <?php if ($ep['duration']): ?>
                                <span class="text-sm text-gray-500 ml-2"><?= $ep['duration'] ?> min</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?= url('admin/series/editEpisode/' . $ep['id']) ?>" 
                               class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="if(confirm('¿Eliminar este episodio?')) window.location='<?= url('admin/series/deleteEpisode/' . $ep['id']) ?>'" 
                                    class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 mb-8 bg-zinc-800/30 rounded-xl">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-800/50 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-300 mb-1">Sin episodios</h3>
            <p class="text-gray-500 text-sm">Agrega el primer episodio usando el formulario de abajo</p>
        </div>
        <?php endif; ?>
        
        <!-- Formulario Agregar Episodio -->
        <form action="<?= url('admin/series/addEpisode/' . $series['id']) ?>" method="POST" enctype="multipart/form-data" 
              class="bg-gradient-to-r from-green-500/10 to-transparent rounded-xl p-6 border border-green-500/20">
            <?= csrfField() ?>
            
            <h3 class="font-bold mb-4 flex items-center gap-2 text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Nuevo Episodio
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Temporada</label>
                    <input type="number" name="season" value="1" min="1" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500 text-center">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Episodio #</label>
                    <input type="number" name="episode_number" value="1" min="1" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500 text-center">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs text-gray-400 mb-1">Título del Episodio *</label>
                    <input type="text" name="ep_title" placeholder="Ej: Piloto" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Duración</label>
                    <input type="number" name="ep_duration" placeholder="min" min="1"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500 text-center">
                </div>
                <div class="row-span-2 flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar
                    </button>
                </div>
                <div class="col-span-2 md:col-span-5">
                    <label class="block text-xs text-gray-400 mb-1">Ruta del Video *</label>
                    <input type="text" name="video_path" placeholder="series/carpeta/video.mp4" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                    <span class="text-xs text-gray-500 mt-1 block">Ruta relativa desde public/media/</span>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
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

// Función para cambiar entre tabs (Información / Episodios)
function switchTab(tab) {
    const sectionInfo = document.getElementById('sectionInfo');
    const sectionEpisodes = document.getElementById('sectionEpisodes');
    const tabInfo = document.getElementById('tabInfo');
    const tabEpisodes = document.getElementById('tabEpisodes');
    
    if (tab === 'info') {
        // Mostrar información, ocultar episodios
        if (sectionInfo) sectionInfo.classList.remove('hidden');
        if (sectionEpisodes) sectionEpisodes.classList.add('hidden');
        // Actualizar estilos de tabs
        if (tabInfo) {
            tabInfo.className = 'px-4 py-2 rounded-lg bg-purple-500/20 text-purple-400 text-sm font-medium transition';
        }
        if (tabEpisodes) {
            tabEpisodes.className = 'px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-medium transition';
        }
    } else if (tab === 'episodes') {
        // Mostrar episodios, ocultar información
        if (sectionInfo) sectionInfo.classList.add('hidden');
        if (sectionEpisodes) sectionEpisodes.classList.remove('hidden');
        // Actualizar estilos de tabs
        if (tabInfo) {
            tabInfo.className = 'px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-medium transition';
        }
        if (tabEpisodes) {
            tabEpisodes.className = 'px-4 py-2 rounded-lg bg-green-500/20 text-green-400 text-sm font-medium transition';
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>

