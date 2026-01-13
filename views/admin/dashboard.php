<?php
/**
 * Vista: Dashboard Admin - Premium
 */
$pageTitle = 'Dashboard';

ob_start();
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-sm text-gray-500">Bienvenido, <?= e(currentUser()['username'] ?? 'Admin') ?></p>
</div>

<!-- Stats cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Usuarios</p>
                <p class="text-3xl font-bold"><?= $stats['users'] ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Películas</p>
                <p class="text-3xl font-bold"><?= $stats['movies'] ?></p>
            </div>
            <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Series</p>
                <p class="text-3xl font-bold"><?= $stats['series'] ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Vistas (Semana)</p>
                <p class="text-3xl font-bold"><?= $stats['views']['weekly_views'] ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Películas recientes -->
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold">Películas Recientes</h2>
            <a href="<?= url('admin/movies/create') ?>" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva
            </a>
        </div>
        <?php if (empty($recentMovies)): ?>
            <div class="text-center py-8">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-white/5 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                </div>
                <p class="text-gray-500 text-sm">No hay películas aún</p>
            </div>
        <?php else: ?>
            <div class="space-y-2">
                <?php foreach ($recentMovies as $movie): ?>
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition">
                    <img src="<?= posterUrl($movie['poster']) ?>" alt="" class="w-10 h-14 object-cover rounded-lg">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate"><?= e($movie['title']) ?></p>
                        <p class="text-xs text-gray-500"><?= e($movie['year'] ?? '') ?></p>
                    </div>
                    <a href="<?= url('admin/movies/edit/' . $movie['id']) ?>" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Series recientes -->
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold">Series Recientes</h2>
            <a href="<?= url('admin/series/create') ?>" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva
            </a>
        </div>
        <?php if (empty($recentSeries)): ?>
            <div class="text-center py-8">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-white/5 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-gray-500 text-sm">No hay series aún</p>
            </div>
        <?php else: ?>
            <div class="space-y-2">
                <?php foreach ($recentSeries as $series): ?>
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition">
                    <img src="<?= posterUrl($series['poster']) ?>" alt="" class="w-10 h-14 object-cover rounded-lg">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate"><?= e($series['title']) ?></p>
                        <p class="text-xs text-gray-500"><?= $series['total_seasons'] ?? 0 ?> temporadas</p>
                    </div>
                    <a href="<?= url('admin/series/edit/' . $series['id']) ?>" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
