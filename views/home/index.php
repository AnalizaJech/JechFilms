<?php
/**
 * Vista: Página de Inicio - Premium con Hero Creativo
 */
$pageTitle = 'Inicio';

// Iconos por categoría
$categoryIcons = [
    'accion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
    'aventura' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'comedia' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'drama' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'terror' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
    'ciencia ficcion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>',
    'romance' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'thriller' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
    'animacion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    'documental' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
];

// Colores por categoría
$categoryColors = [
    'accion' => ['from-orange-500 to-red-600', 'bg-orange-500', 'text-orange-400', 'shadow-orange-500/30'],
    'aventura' => ['from-emerald-500 to-teal-600', 'bg-emerald-500', 'text-emerald-400', 'shadow-emerald-500/30'],
    'comedia' => ['from-yellow-400 to-orange-500', 'bg-yellow-500', 'text-yellow-400', 'shadow-yellow-500/30'],
    'drama' => ['from-red-500 to-rose-600', 'bg-red-500', 'text-red-400', 'shadow-red-500/30'],
    'terror' => ['from-slate-600 to-slate-800', 'bg-slate-600', 'text-slate-400', 'shadow-slate-500/30'],
    'ciencia ficcion' => ['from-cyan-400 to-blue-600', 'bg-cyan-500', 'text-cyan-400', 'shadow-cyan-500/30'],
    'romance' => ['from-pink-500 to-rose-600', 'bg-pink-500', 'text-pink-400', 'shadow-pink-500/30'],
    'thriller' => ['from-violet-500 to-purple-700', 'bg-violet-500', 'text-violet-400', 'shadow-violet-500/30'],
    'animacion' => ['from-blue-500 to-indigo-600', 'bg-blue-500', 'text-blue-400', 'shadow-blue-500/30'],
    'documental' => ['from-teal-400 to-cyan-600', 'bg-teal-500', 'text-teal-400', 'shadow-teal-500/30'],
];

$defaultIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>';

ob_start();
?>

<!-- Hero Section -->
<?php if (!empty($heroContent)): ?>
<section class="relative min-h-screen flex items-center">
    <div class="absolute inset-0">
        <img 
            src="<?= posterUrl($heroContent['backdrop'] ?? $heroContent['poster'] ?? null) ?>" 
            alt="<?= e($heroContent['title']) ?>"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0a]/90 via-[#0a0a0a]/30 to-transparent"></div>
    </div>
    
    <div class="relative container mx-auto px-6">
        <div class="max-w-2xl">
            <span class="inline-block px-3 py-1.5 bg-red-600 rounded-lg text-xs font-bold uppercase tracking-wide mb-4">
                <?= isset($heroContent['video_path']) ? 'Película' : 'Serie' ?>
            </span>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-[1.1]"><?= e($heroContent['title']) ?></h1>
            
            <?php if (!empty($heroContent['description'])): ?>
            <p class="text-lg text-gray-300 mb-8 line-clamp-2 leading-relaxed max-w-xl"><?= e($heroContent['description']) ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap items-center gap-4">
                <a href="<?= url((isset($heroContent['video_path']) ? 'watch/movie/' : 'series/') . $heroContent['id']) ?>" 
                   class="inline-flex items-center gap-3 bg-white text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Reproducir
                </a>
                <a href="<?= url((isset($heroContent['video_path']) ? 'movies/' : 'series/') . $heroContent['id']) ?>" 
                   class="inline-flex items-center gap-3 bg-white/15 backdrop-blur-sm px-6 py-4 rounded-xl font-semibold hover:bg-white/25 transition border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Más Info
                </a>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<!-- Hero sin contenido - Diseño Responsive -->
<section class="relative overflow-hidden pt-20 md:pt-0" style="min-height: calc(100vh - 64px); margin-top: 64px;">
    <!-- Fondo con gradientes -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/20 via-transparent to-purple-900/20"></div>
        <div class="absolute top-1/4 left-1/4 w-64 md:w-96 h-64 md:h-96 bg-red-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 md:w-96 h-64 md:h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Contenido centrado -->
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center max-w-3xl px-4 md:px-6">
            <!-- Logo -->
            <div class="relative w-20 h-20 md:w-28 md:h-28 mx-auto mb-6 md:mb-10">
                <div class="absolute inset-0 border-2 border-red-500/20 rounded-full animate-pulse"></div>
                <div class="absolute -inset-3 md:-inset-4 border border-red-500/10 rounded-full"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl md:rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-xl shadow-red-500/30">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Título -->
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6">
                Bienvenido a
                <span class="block mt-1 md:mt-2 bg-gradient-to-r from-red-400 via-red-500 to-rose-500 bg-clip-text text-transparent">
                    Jech Films
                </span>
            </h1>
            
            <!-- Descripción -->
            <p class="text-base md:text-xl text-gray-400 mb-6 md:mb-10 leading-relaxed max-w-lg mx-auto">
                Tu plataforma de streaming multimedia local.
                <span class="text-gray-500 block md:inline"> Organiza, descubre y disfruta tu contenido.</span>
            </p>
            
            <!-- Features mini -->
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 mb-6 md:mb-10">
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Películas</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Series</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Caja Fuerte</span>
                </div>
            </div>
            
            <!-- Botones -->
            <?php if (!isAuthenticated()): ?>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= url('register') ?>" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-base md:text-lg transition-all shadow-xl shadow-red-500/25">
                    Comenzar Ahora
                    <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="<?= url('login') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-semibold transition border border-white/10">
                    Ya tengo cuenta
                </a>
            </div>
            <?php else: ?>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= url('movies') ?>" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-base md:text-lg transition-all shadow-xl shadow-red-500/25">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    Explorar Películas
                </a>
                <a href="<?= url('series') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-semibold transition border border-white/10">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Ver Series
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contenido -->
<div class="container mx-auto px-6 py-16 space-y-20">
    
    <!-- Categorías Creativas -->
    <?php if (!empty($categories)): ?>
    <section>
        <h2 class="text-2xl font-bold mb-8">Explorar por Categoría</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($categories as $category): 
                $slug = strtolower($category['name']);
                $colors = $categoryColors[$slug] ?? ['from-gray-500 to-gray-700', 'bg-gray-500', 'text-gray-400', 'shadow-gray-500/30'];
                $icon = $categoryIcons[$slug] ?? $defaultIcon;
                $count = ($category['movies_count'] ?? 0) + ($category['series_count'] ?? 0);
            ?>
            <a href="<?= url('movies?category=' . $category['id']) ?>" 
               class="group relative overflow-hidden rounded-2xl bg-gradient-to-br <?= $colors[0] ?> p-[1px] transition-all hover:scale-[1.02]">
                <div class="relative bg-[#0f0f0f] rounded-[15px] p-5 h-full">
                    <div class="absolute inset-0 bg-gradient-to-br <?= $colors[0] ?> opacity-0 group-hover:opacity-10 rounded-[15px] transition-opacity"></div>
                    <div class="relative w-12 h-12 mb-4 rounded-xl <?= $colors[1] ?> flex items-center justify-center shadow-lg <?= $colors[3] ?> group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $icon ?></svg>
                    </div>
                    <h3 class="font-semibold mb-1 relative"><?= e($category['name']) ?></h3>
                    <p class="text-sm <?= $colors[2] ?> relative"><?= $count ?> <?= $count === 1 ? 'título' : 'títulos' ?></p>
                    <div class="absolute bottom-5 right-5 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                        <svg class="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Películas en Tendencia -->
    <?php if (!empty($trendingMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center text-lg">🔥</span>
                Películas en Tendencia
            </h2>
            <a href="<?= url('movies?sort=popular') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($trendingMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Series en Tendencia -->
    <?php if (!empty($trendingSeries)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-lg">📺</span>
                Series en Tendencia
            </h2>
            <a href="<?= url('series?sort=popular') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($trendingSeries, 0, 6) as $series): ?>
                <?php $item = $series; include VIEWS_PATH . '/components/series-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Más gustadas -->
    <?php if (!empty($likedMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center text-lg">❤️</span>
                Más Gustadas
            </h2>
            <a href="<?= url('movies?sort=liked') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($likedMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Recientes -->
    <?php if (!empty($recentMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center text-lg">🆕</span>
                Agregados Recientemente
            </h2>
            <a href="<?= url('movies?sort=recent') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($recentMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
