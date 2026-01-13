<?php
/**
 * Vista: Mi Lista - Rediseñada
 */
$pageTitle = 'Mi Lista';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6">
        
        <h1 class="text-3xl font-bold mb-8">Mi Lista</h1>
        
        <?php if (empty($items)): ?>
            <div class="text-center py-24">
                <!-- Icono animado -->
                <div class="w-24 h-24 mx-auto mb-8 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center border border-white/5">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-200 mb-3">Tu lista está vacía</h2>
                <p class="text-gray-500 mb-10 max-w-md mx-auto">
                    Guarda películas y series para verlas más tarde. Solo haz clic en el ícono de guardar en cualquier contenido.
                </p>
                
                <!-- Botones de explorar -->
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="<?= url('movies') ?>" class="inline-flex items-center gap-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-8 py-4 rounded-xl font-semibold transition shadow-lg shadow-red-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        Explorar Películas
                    </a>
                    <a href="<?= url('series') ?>" class="inline-flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 px-8 py-4 rounded-xl font-semibold transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Explorar Series
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <?php foreach ($items as $item): ?>
                <div class="group card-hover">
                    <a href="<?= url($item['content_type'] . 's/' . $item['content_id']) ?>" class="block">
                        <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-[#161616]">
                            <img src="<?= posterUrl($item['poster']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                            
                            <!-- Badge de tipo -->
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-xs font-bold uppercase <?= $item['content_type'] === 'movie' ? 'bg-red-600' : 'bg-purple-600' ?>">
                                <?= $item['content_type'] === 'movie' ? 'Película' : 'Serie' ?>
                            </div>
                            
                            <!-- Overlay hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="font-bold text-sm line-clamp-2"><?= e($item['title']) ?></h3>
                                    <?php if (!empty($item['year'])): ?>
                                    <span class="text-xs text-gray-400"><?= e($item['year']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Play button -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <div class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center transform scale-75 group-hover:scale-100 transition">
                                    <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 px-1">
                            <h3 class="font-medium text-sm truncate"><?= e($item['title']) ?></h3>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
