<?php
/**
 * Vista: Resultados de Búsqueda - Pantalla Completa
 */
$pageTitle = !empty($query) ? 'Buscar: ' . e($query) : 'Buscar';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6">
        
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Buscar Contenido</h1>
            <p class="text-gray-500 text-sm">Encuentra películas y series en tu biblioteca</p>
        </div>
        
        <!-- Formulario de búsqueda -->
        <form action="<?= url('search') ?>" method="GET" class="max-w-xl mx-auto mb-8">
            <div class="relative">
                <input 
                    type="text" 
                    name="q" 
                    value="<?= e($query ?? '') ?>" 
                    placeholder="¿Qué quieres ver hoy?"
                    autofocus
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 pr-12 text-base placeholder-gray-500 focus:border-red-500 focus:outline-none transition"
                >
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-red-600 hover:bg-red-700 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
        
        <?php if (empty($query)): ?>
            <!-- Estado inicial -->
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-5 rounded-xl bg-white/5 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-300 mb-2">Descubre contenido</h2>
                <p class="text-gray-500 text-sm mb-6">Escribe el nombre de una película o serie para comenzar</p>
                
                <!-- Sugerencias -->
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <span class="text-xs text-gray-500">Prueba buscar:</span>
                    <a href="<?= url('search?q=acción') ?>" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs transition">Acción</a>
                    <a href="<?= url('search?q=comedia') ?>" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs transition">Comedia</a>
                    <a href="<?= url('search?q=drama') ?>" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-xs transition">Drama</a>
                </div>
            </div>
            
        <?php elseif (empty($movies) && empty($series)): ?>
            <!-- Sin resultados -->
            <div class="text-center py-24">
                <div class="w-24 h-24 mx-auto mb-8 rounded-2xl bg-white/5 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-300 mb-2">Sin resultados</h2>
                <p class="text-gray-500 mb-8">No encontramos nada para "<span class="text-white"><?= e($query) ?></span>"</p>
                
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="<?= url('movies') ?>" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                        Explorar Películas
                    </a>
                    <a href="<?= url('series') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/10 px-6 py-3 rounded-xl font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Explorar Series
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Resultados encontrados -->
            <div class="text-center mb-10">
                <p class="text-gray-400">
                    <span class="text-white font-semibold"><?= count($movies) + count($series) ?></span> resultados para "<span class="text-white"><?= e($query) ?></span>"
                </p>
            </div>
            
            <?php if (!empty($movies)): ?>
            <section class="mb-16">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                        </span>
                        Películas
                    </h2>
                    <span class="text-sm text-gray-500"><?= count($movies) ?> encontradas</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                    <?php foreach ($movies as $movie): ?>
                        <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($series)): ?>
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </span>
                        Series
                    </h2>
                    <span class="text-sm text-gray-500"><?= count($series) ?> encontradas</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                    <?php foreach ($series as $item): ?>
                        <?php include VIEWS_PATH . '/components/series-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        <?php endif; ?>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
