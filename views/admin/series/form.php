<?php
/**
 * Vista: Formulario de Serie (Admin)
 */
$isEdit = isset($series);
$pageTitle = $isEdit ? 'Editar Serie' : 'Nueva Serie';

ob_start();
?>

<div class="max-w-5xl">
    <h1 class="text-3xl font-bold mb-8"><?= $pageTitle ?></h1>
    
    <!-- Formulario de serie -->
    <form action="<?= url('admin/series/' . ($isEdit ? 'update/' . $series['id'] : 'store')) ?>" method="POST" enctype="multipart/form-data" class="bg-jf-card rounded-xl p-8 mb-8">
        <?= csrfField() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Título *</label>
                <input type="text" name="title" value="<?= e($series['title'] ?? '') ?>" required
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Descripción</label>
                <textarea name="description" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red"><?= e($series['description'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Año Inicio</label>
                <input type="number" name="year_start" value="<?= e($series['year_start'] ?? '') ?>" min="1900" max="2030"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Año Fin (vacío si en emisión)</label>
                <input type="number" name="year_end" value="<?= e($series['year_end'] ?? '') ?>" min="1900" max="2030"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Total Temporadas</label>
                <input type="number" name="total_seasons" value="<?= e($series['total_seasons'] ?? 1) ?>" min="1"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Poster</label>
                <input type="file" name="poster" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-jf-red file:text-white">
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Backdrop</label>
                <input type="file" name="backdrop" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-jf-red file:text-white">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Categorías</label>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($categories as $cat): ?>
                    <label class="inline-flex items-center gap-2 bg-zinc-800 px-3 py-2 rounded cursor-pointer hover:bg-zinc-700">
                        <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" <?= (isset($selectedCategories) && in_array($cat['id'], $selectedCategories)) ? 'checked' : '' ?>>
                        <span class="text-sm"><?= e($cat['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="md:col-span-2 flex gap-6">
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" <?= ($series['is_featured'] ?? false) ? 'checked' : '' ?>> Destacar</label>
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_vault" value="1" <?= ($series['is_vault'] ?? false) ? 'checked' : '' ?>> Caja Fuerte</label>
            </div>
        </div>
        
        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-white/10">
            <button type="submit" class="bg-jf-red hover:bg-red-700 px-6 py-3 rounded-lg font-medium"><?= $isEdit ? 'Guardar' : 'Crear' ?></button>
            <a href="<?= url('admin/series') ?>" class="text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
    
    <!-- Gestión de episodios (solo en edición) -->
    <?php if ($isEdit): ?>
    <div class="bg-jf-card rounded-xl p-8">
        <h2 class="text-xl font-bold mb-6">Episodios</h2>
        
        <!-- Episodios existentes -->
        <?php if (!empty($seasons)): ?>
        <div class="space-y-4 mb-8">
            <?php foreach ($seasons as $seasonNum => $episodes): ?>
            <div>
                <h3 class="text-sm font-medium text-gray-400 mb-2">Temporada <?= $seasonNum ?></h3>
                <div class="space-y-2">
                    <?php foreach ($episodes as $ep): ?>
                    <div class="flex items-center justify-between bg-zinc-800 rounded p-3">
                        <span>E<?= $ep['episode_number'] ?>: <?= e($ep['title']) ?></span>
                        <a href="<?= url('admin/series/deleteEpisode/' . $ep['id']) ?>" onclick="return confirm('¿Eliminar episodio?')" class="text-red-500 text-sm">Eliminar</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- Agregar episodio -->
        <form action="<?= url('admin/series/addEpisode/' . $series['id']) ?>" method="POST" enctype="multipart/form-data" class="border-t border-white/10 pt-6">
            <?= csrfField() ?>
            <h3 class="font-medium mb-4">Agregar Episodio</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="number" name="season" placeholder="Temporada" min="1" value="1" class="bg-zinc-800 border border-zinc-700 rounded px-3 py-2">
                <input type="number" name="episode_number" placeholder="# Episodio" min="1" value="1" class="bg-zinc-800 border border-zinc-700 rounded px-3 py-2">
                <input type="text" name="ep_title" placeholder="Título del episodio" required class="bg-zinc-800 border border-zinc-700 rounded px-3 py-2">
                <input type="number" name="ep_duration" placeholder="Duración (min)" class="bg-zinc-800 border border-zinc-700 rounded px-3 py-2">
                <input type="text" name="ep_video_path" placeholder="Ruta video (series/...)" required class="bg-zinc-800 border border-zinc-700 rounded px-3 py-2 md:col-span-2">
            </div>
            <button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Agregar Episodio</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
