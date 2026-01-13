<?php
/**
 * Vista: Formulario de Película (Admin)
 */
$isEdit = isset($movie);
$pageTitle = $isEdit ? 'Editar Película' : 'Nueva Película';

ob_start();
?>

<div class="max-w-4xl">
    <h1 class="text-3xl font-bold mb-8"><?= $pageTitle ?></h1>
    
    <form action="<?= url('admin/movies/' . ($isEdit ? 'update/' . $movie['id'] : 'store')) ?>" method="POST" enctype="multipart/form-data" class="bg-jf-card rounded-xl p-8">
        <?= csrfField() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Título *</label>
                <input type="text" name="title" value="<?= e($movie['title'] ?? '') ?>" required
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Descripción</label>
                <textarea name="description" rows="4" 
                          class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red"><?= e($movie['description'] ?? '') ?></textarea>
            </div>
            
            <!-- Año -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Año</label>
                <input type="number" name="year" value="<?= e($movie['year'] ?? '') ?>" min="1900" max="2030"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <!-- Duración -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Duración (minutos)</label>
                <input type="number" name="duration" value="<?= e($movie['duration'] ?? '') ?>" min="1"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <!-- Ruta del video -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Ruta del Video *</label>
                <input type="text" name="video_path" value="<?= e($movie['video_path'] ?? '') ?>" required placeholder="movies/mi-pelicula.mp4"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
                <p class="text-xs text-gray-500 mt-1">Ruta relativa desde la carpeta /media/</p>
            </div>
            
            <!-- Poster -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Poster</label>
                <?php if ($isEdit && $movie['poster']): ?>
                    <img src="<?= posterUrl($movie['poster']) ?>" alt="" class="w-20 h-28 object-cover rounded mb-2">
                <?php endif; ?>
                <input type="file" name="poster" accept="image/*"
                       class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-jf-red file:text-white">
            </div>
            
            <!-- Backdrop -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Backdrop (Fondo)</label>
                <?php if ($isEdit && $movie['backdrop']): ?>
                    <img src="<?= posterUrl($movie['backdrop']) ?>" alt="" class="w-32 h-20 object-cover rounded mb-2">
                <?php endif; ?>
                <input type="file" name="backdrop" accept="image/*"
                       class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-jf-red file:text-white">
            </div>
            
            <!-- Categorías -->
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Categorías</label>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($categories as $cat): ?>
                    <label class="inline-flex items-center gap-2 bg-zinc-800 px-3 py-2 rounded cursor-pointer hover:bg-zinc-700">
                        <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" 
                               <?= (isset($selectedCategories) && in_array($cat['id'], $selectedCategories)) ? 'checked' : '' ?>
                               class="rounded border-zinc-600 text-jf-red focus:ring-jf-red">
                        <span class="text-sm"><?= e($cat['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Opciones -->
            <div class="md:col-span-2 flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" <?= ($movie['is_featured'] ?? false) ? 'checked' : '' ?>
                           class="rounded border-zinc-600 text-jf-red focus:ring-jf-red">
                    <span>Destacar en inicio</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_vault" value="1" <?= ($movie['is_vault'] ?? false) ? 'checked' : '' ?>
                           class="rounded border-zinc-600 text-pink-500 focus:ring-pink-500">
                    <span>Contenido de Caja Fuerte</span>
                </label>
            </div>
        </div>
        
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-jf-red hover:bg-red-700 px-6 py-3 rounded-lg font-medium transition">
                <?= $isEdit ? 'Guardar Cambios' : 'Crear Película' ?>
            </button>
            <a href="<?= url('admin/movies') ?>" class="text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
