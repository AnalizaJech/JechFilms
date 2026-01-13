<?php
/**
 * Vista: Detalle de Serie con Episodios
 */
$pageTitle = $series['title'];

ob_start();
?>

<!-- Hero -->
<div class="relative min-h-[60vh]">
    <div class="absolute inset-0">
        <img src="<?= posterUrl($series['backdrop'] ?? $series['poster']) ?>" alt="<?= e($series['title']) ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-jf-dark via-jf-dark/70 to-transparent"></div>
    </div>
    
    <div class="relative container mx-auto px-4 pt-32 pb-12">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-shrink-0">
                <img src="<?= posterUrl($series['poster']) ?>" alt="<?= e($series['title']) ?>" class="w-52 rounded-lg shadow-2xl mx-auto md:mx-0">
            </div>
            
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-4"><?= e($series['title']) ?></h1>
                
                <div class="flex flex-wrap items-center gap-4 text-gray-300 mb-4">
                    <span><?= e($series['year_start']) ?><?= $series['year_end'] ? ' - ' . $series['year_end'] : '' ?></span>
                    <span>•</span>
                    <span><?= $series['total_seasons'] ?> Temporada<?= $series['total_seasons'] > 1 ? 's' : '' ?></span>
                    <span>•</span>
                    <span><?= $series['total_episodes'] ?? 0 ?> Episodios</span>
                    <?php if ($series['categories']): ?>
                        <span class="text-jf-red"><?= e($series['categories']) ?></span>
                    <?php endif; ?>
                </div>
                
                <p class="text-gray-300 leading-relaxed mb-6 max-w-3xl"><?= e($series['description'] ?? '') ?></p>
                
                <!-- Acciones -->
                <div class="flex flex-wrap items-center gap-4">
                    <?php 
                    $firstEpisode = null;
                    if (!empty($seasons)) {
                        $firstSeason = reset($seasons);
                        $firstEpisode = reset($firstSeason);
                    }
                    ?>
                    <?php if ($firstEpisode): ?>
                    <a href="<?= url('watch/episode/' . $firstEpisode['id']) ?>" 
                       class="inline-flex items-center gap-2 bg-white text-black px-8 py-3 rounded font-semibold hover:bg-gray-200 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Reproducir T1:E1
                    </a>
                    <?php endif; ?>
                    
                    <?php if (isAuthenticated()): ?>
                    <button onclick="toggleList('series', <?= $series['id'] ?>)" 
                            class="inline-flex items-center gap-2 bg-gray-600/50 text-white px-6 py-3 rounded font-medium hover:bg-gray-600 transition">
                        <svg class="w-5 h-5" fill="<?= $inList ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <?= $inList ? 'En Mi Lista' : 'Mi Lista' ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Episodios por temporada -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6">Episodios</h2>
    
    <?php if (empty($seasons)): ?>
        <p class="text-gray-400">No hay episodios disponibles aún.</p>
    <?php else: ?>
        <!-- Tabs de temporadas -->
        <div class="flex flex-wrap gap-2 mb-6" id="season-tabs">
            <?php foreach (array_keys($seasons) as $i => $seasonNum): ?>
            <button 
                onclick="showSeason(<?= $seasonNum ?>)"
                class="px-4 py-2 rounded-lg transition season-tab <?= $i === 0 ? 'bg-jf-red' : 'bg-jf-card hover:bg-jf-card-hover' ?>"
                data-season="<?= $seasonNum ?>"
            >
                Temporada <?= $seasonNum ?>
            </button>
            <?php endforeach; ?>
        </div>
        
        <!-- Lista de episodios -->
        <?php foreach ($seasons as $seasonNum => $episodes): ?>
        <div class="season-content <?= $seasonNum !== array_key_first($seasons) ? 'hidden' : '' ?>" data-season="<?= $seasonNum ?>">
            <div class="space-y-4">
                <?php foreach ($episodes as $ep): ?>
                <a href="<?= url('watch/episode/' . $ep['id']) ?>" 
                   class="flex gap-4 bg-jf-card rounded-lg p-4 hover:bg-jf-card-hover transition group">
                    <!-- Thumbnail -->
                    <div class="relative flex-shrink-0 w-40 aspect-video rounded overflow-hidden bg-jf-darker">
                        <img src="<?= posterUrl($ep['thumbnail'] ?? $series['poster']) ?>" alt="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm text-jf-red font-medium">E<?= $ep['episode_number'] ?></span>
                            <h3 class="font-semibold truncate"><?= e($ep['title']) ?></h3>
                        </div>
                        <p class="text-sm text-gray-400 line-clamp-2"><?= e($ep['description'] ?? '') ?></p>
                        <?php if ($ep['duration']): ?>
                        <span class="text-xs text-gray-500 mt-2 inline-block"><?= formatDuration($ep['duration']) ?></span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function showSeason(num) {
    document.querySelectorAll('.season-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.season-tab').forEach(el => {
        el.classList.remove('bg-jf-red');
        el.classList.add('bg-jf-card');
    });
    document.querySelector(`.season-content[data-season="${num}"]`).classList.remove('hidden');
    document.querySelector(`.season-tab[data-season="${num}"]`).classList.add('bg-jf-red');
    document.querySelector(`.season-tab[data-season="${num}"]`).classList.remove('bg-jf-card');
}

function toggleList(type, id) {
    fetch('<?= url('api/list/toggle') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({type, id})
    }).then(() => location.reload());
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
