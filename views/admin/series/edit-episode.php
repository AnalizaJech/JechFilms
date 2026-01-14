<?php
/**
 * Vista: Editar Episodio (Admin) - Modal/Página dedicada
 */
$pageTitle = 'Editar Episodio - ' . $series['title'];

ob_start();
?>

<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
}
</style>

<div class="max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="<?= url('admin/series') ?>" class="hover:text-white transition">Series</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="<?= url('admin/series/edit/' . $series['id']) ?>" class="hover:text-white transition"><?= e($series['title']) ?></a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">Editar Episodio</span>
    </div>

    <div class="bg-jf-card rounded-2xl p-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Editar Episodio</h1>
                <p class="text-gray-400">T<?= $episode['season'] ?> E<?= $episode['episode_number'] ?>: <?= e($episode['title']) ?></p>
            </div>
        </div>
        
        <form action="<?= url('admin/series/updateEpisode/' . $episode['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Temporada y Número de Episodio -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Temporada</label>
                    <input type="number" name="season" value="<?= e($episode['season']) ?>" min="1" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Número de Episodio</label>
                    <input type="number" name="episode_number" value="<?= e($episode['episode_number']) ?>" min="1" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                </div>
                
                <!-- Título -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Título del Episodio *</label>
                    <input type="text" name="title" value="<?= e($episode['title']) ?>" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                </div>
                
                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Descripción</label>
                    <textarea name="description" rows="3" 
                              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 resize-none"><?= e($episode['description'] ?? '') ?></textarea>
                </div>
                
                <!-- Duración -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Duración (minutos)</label>
                    <input type="number" name="duration" value="<?= e($episode['duration'] ?? '') ?>" min="1"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                </div>
                
                <!-- Ruta del Video -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Ruta del Video *</label>
                    <input type="text" name="video_path" value="<?= e($episode['video_path']) ?>" required placeholder="series/nombre/s01e01.mp4"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500">
                </div>
                
                <!-- Thumbnail -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Thumbnail (Miniatura)</label>
                    <?php if (!empty($episode['thumbnail'])): ?>
                        <img src="<?= posterUrl($episode['thumbnail']) ?>" alt="" class="w-32 h-20 object-cover rounded-lg mb-2">
                    <?php endif; ?>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:cursor-pointer">
                </div>
            </div>
            
            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-xl font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Cambios
                </button>
                <a href="<?= url('admin/series/edit/' . $series['id']) ?>" class="text-gray-400 hover:text-white transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
