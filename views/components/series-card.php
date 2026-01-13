<!-- Componente: Card de Serie Premium -->
<?php $item = $item ?? $series ?? null; if (!$item) return; ?>
<div class="group card-hover relative">
    <a href="<?= url('series/' . $item['id']) ?>" class="block">
        <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-jf-card shadow-lg">
            <!-- Imagen -->
            <img 
                src="<?= posterUrl($item['poster'] ?? null) ?>" 
                alt="<?= e($item['title']) ?>"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                loading="lazy"
            >
            
            <!-- Overlay gradiente -->
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Badge de temporadas -->
            <div class="absolute top-3 left-3 bg-purple-600/90 backdrop-blur-sm px-2 py-1 rounded-lg text-xs font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <?= $item['total_seasons'] ?? 1 ?> temp.
            </div>
            
            <!-- Contenido hover -->
            <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                <h3 class="font-bold text-sm mb-1 line-clamp-2 drop-shadow-lg"><?= e($item['title']) ?></h3>
                <div class="flex items-center gap-2 text-xs text-gray-300">
                    <?php if (!empty($item['year_start'])): ?>
                        <span><?= e($item['year_start']) ?><?= !empty($item['year_end']) ? ' - ' . $item['year_end'] : '' ?></span>
                    <?php endif; ?>
                    <?php if (!empty($item['total_episodes'])): ?>
                        <span>• <?= $item['total_episodes'] ?> eps</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Icono central -->
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <div class="w-14 h-14 rounded-full bg-purple-600 flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-2xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Título debajo -->
        <div class="mt-3 px-1">
            <h3 class="font-medium text-sm truncate group-hover:text-purple-400 transition-colors"><?= e($item['title']) ?></h3>
            <?php if (!empty($item['categories'])): ?>
            <p class="text-xs text-gray-500 truncate"><?= e($item['categories']) ?></p>
            <?php endif; ?>
        </div>
    </a>
</div>
