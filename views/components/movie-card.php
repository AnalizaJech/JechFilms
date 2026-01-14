<!-- Componente: Card de Película Premium con Play y título clickeable -->
<?php
$movieData = json_encode([
    'id' => $movie['id'],
    'title' => $movie['title'],
    'description' => $movie['description'] ?? '',
    'poster' => $movie['poster'] ?? '',
    'backdrop' => $movie['backdrop'] ?? $movie['poster'] ?? '',
    'year' => $movie['year'] ?? null,
    'duration' => $movie['duration'] ?? null,
    'categories' => $movie['categories'] ?? '',
    'video_path' => $movie['video_path'] ?? ''
], JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<div class="group relative">
    <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-jf-card shadow-lg cursor-pointer" onclick='openInfoModal(<?= $movieData ?>)'>
        <!-- Imagen -->
        <img 
            src="<?= posterUrl($movie['poster'] ?? null) ?>" 
            alt="<?= e($movie['title']) ?>"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            loading="lazy"
        >
        
        <!-- Overlay gradiente -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <!-- Badge de año -->
        <?php if (!empty($movie['year'])): ?>
        <div class="absolute top-2 left-2 md:top-3 md:left-3 bg-red-600 px-2 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[10px] md:text-xs font-bold">
            <?= e($movie['year']) ?>
        </div>
        <?php endif; ?>
        
        <!-- Botón Play central en hover -->
        <a href="<?= url('watch/movie/' . $movie['id']) ?>" 
           class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 z-10" 
           onclick="event.stopPropagation()">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white/95 hover:bg-white flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-xl">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </a>
        
        <!-- Info en hover -->
        <div class="absolute bottom-0 left-0 right-0 p-3 md:p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-5">
            <h3 class="font-bold text-xs md:text-sm line-clamp-2"><?= e($movie['title']) ?></h3>
            <?php if (!empty($movie['duration'])): ?>
            <span class="text-[10px] md:text-xs text-gray-400"><?= formatDuration($movie['duration']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Título debajo de la card (clickeable abre modal) -->
    <div class="mt-2 px-1">
        <h3 class="font-medium text-xs md:text-sm text-gray-200 truncate cursor-pointer hover:text-red-400 transition" 
            onclick='openInfoModal(<?= $movieData ?>)'><?= e($movie['title']) ?></h3>
        <?php if (!empty($movie['duration'])): ?>
        <span class="text-[10px] text-gray-500"><?= formatDuration($movie['duration']) ?></span>
        <?php endif; ?>
    </div>
</div>
