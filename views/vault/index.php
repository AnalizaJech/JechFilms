<?php
/**
 * Vista: Contenido de Caja Fuerte - Premium
 */
$pageTitle = 'Caja Fuerte';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6">
        
        <!-- Header Premium -->
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/25">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Caja Fuerte</h1>
                    <p class="text-gray-500 text-sm">Contenido exclusivo para adultos</p>
                </div>
            </div>
            
            <a href="<?= url('vault/lock') ?>" class="flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/10 px-5 py-2.5 rounded-xl font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Bloquear
            </a>
        </div>
        
        <!-- Películas -->
        <?php if (!empty($movies)): ?>
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </span>
                    Películas
                </h2>
                <span class="text-sm text-gray-500"><?= count($movies) ?> títulos</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <?php foreach ($movies as $movie): ?>
                    <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Series -->
        <?php if (!empty($series)): ?>
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </span>
                    Series
                </h2>
                <span class="text-sm text-gray-500"><?= count($series) ?> títulos</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <?php foreach ($series as $item): ?>
                    <?php include VIEWS_PATH . '/components/series-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Estado vacío -->
        <?php if (empty($movies) && empty($series)): ?>
        <div class="text-center py-24">
            <div class="w-24 h-24 mx-auto mb-8 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 border border-amber-500/20 flex items-center justify-center">
                <svg class="w-12 h-12 text-amber-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-200 mb-3">La caja fuerte está vacía</h2>
            <p class="text-gray-500 mb-10 max-w-md mx-auto">
                Aquí aparecerá el contenido marcado como privado. Puedes agregar películas y series desde el panel de administración.
            </p>
            
            <?php if (isAdmin()): ?>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="<?= url('admin/movies/create') ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 rounded-xl font-medium transition shadow-lg shadow-red-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar Película
                </a>
                <a href="<?= url('admin/series/create') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/10 px-6 py-3 rounded-xl font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar Serie
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
