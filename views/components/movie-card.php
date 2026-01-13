<!-- Componente: Card de Película Premium -->
<div class="group card-hover relative">
    <a href="<?= url('movies/' . $movie['id']) ?>" class="block">
        <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-jf-card shadow-lg">
            <!-- Imagen -->
            <img 
                src="<?= posterUrl($movie['poster'] ?? null) ?>" 
                alt="<?= e($movie['title']) ?>"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                loading="lazy"
            >
            
            <!-- Overlay gradiente -->
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Badge de año -->
            <?php if (!empty($movie['year'])): ?>
            <div class="absolute top-3 left-3 bg-black/70 backdrop-blur-sm px-2 py-1 rounded-lg text-xs font-medium">
                <?= e($movie['year']) ?>
            </div>
            <?php endif; ?>
            
            <!-- Contenido hover -->
            <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                <h3 class="font-bold text-sm mb-1 line-clamp-2 drop-shadow-lg"><?= e($movie['title']) ?></h3>
                <div class="flex items-center gap-2 text-xs text-gray-300">
                    <?php if (!empty($movie['duration'])): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?= formatDuration($movie['duration']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($movie['likes_count'])): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <?= $movie['likes_count'] ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Botón de play central -->
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <div class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-2xl">
                    <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Título debajo (visible siempre) -->
        <div class="mt-3 px-1">
            <h3 class="font-medium text-sm truncate group-hover:text-red-400 transition-colors"><?= e($movie['title']) ?></h3>
            <?php if (!empty($movie['categories'])): ?>
            <p class="text-xs text-gray-500 truncate"><?= e($movie['categories']) ?></p>
            <?php endif; ?>
        </div>
    </a>
</div>
