<?php
/**
 * Vista: Detalle de Película
 */
$pageTitle = $movie['title'];

ob_start();
?>

<!-- Hero con backdrop -->
<div class="relative min-h-[70vh]">
    <div class="absolute inset-0">
        <img 
            src="<?= posterUrl($movie['backdrop'] ?? $movie['poster']) ?>" 
            alt="<?= e($movie['title']) ?>"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-jf-dark via-jf-dark/70 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-jf-dark via-transparent to-transparent"></div>
    </div>
    
    <div class="relative container mx-auto px-4 pt-32 pb-12">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Poster -->
            <div class="flex-shrink-0">
                <img 
                    src="<?= posterUrl($movie['poster']) ?>" 
                    alt="<?= e($movie['title']) ?>"
                    class="w-64 rounded-lg shadow-2xl mx-auto md:mx-0"
                >
            </div>
            
            <!-- Info -->
            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?= e($movie['title']) ?></h1>
                
                <!-- Meta info -->
                <div class="flex flex-wrap items-center gap-4 text-gray-300 mb-6">
                    <?php if ($movie['year']): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?= e($movie['year']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($movie['duration']): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?= formatDuration($movie['duration']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($movie['categories']): ?>
                        <span class="text-jf-red"><?= e($movie['categories']) ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Estadísticas -->
                <div class="flex items-center gap-6 mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span><?= $movie['likes_count'] ?? 0 ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span><?= $movie['views_count'] ?? 0 ?> vistas</span>
                    </div>
                </div>
                
                <!-- Descripción -->
                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-3xl">
                    <?= e($movie['description'] ?? 'Sin descripción disponible.') ?>
                </p>
                
                <!-- Acciones -->
                <div class="flex flex-wrap items-center gap-4">
                    <a href="<?= url('watch/movie/' . $movie['id']) ?>" 
                       class="inline-flex items-center gap-2 bg-white text-black px-8 py-3 rounded font-semibold hover:bg-gray-200 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        Reproducir
                    </a>
                    
                    <?php if (isAuthenticated()): ?>
                        <!-- Botón Mi Lista -->
                        <button onclick="toggleList('movie', <?= $movie['id'] ?>)" 
                                id="list-btn"
                                class="inline-flex items-center gap-2 bg-gray-600/50 text-white px-6 py-3 rounded font-medium hover:bg-gray-600 transition">
                            <svg class="w-5 h-5" fill="<?= $inList ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span><?= $inList ? 'En Mi Lista' : 'Mi Lista' ?></span>
                        </button>
                        
                        <!-- Like -->
                        <button onclick="react('movie', <?= $movie['id'] ?>, 'like')"
                                class="p-3 rounded-full bg-gray-600/50 hover:bg-green-600 transition <?= $userReaction === 'like' ? 'bg-green-600' : '' ?>">
                            <svg class="w-5 h-5" fill="<?= $userReaction === 'like' ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </button>
                        
                        <!-- Dislike -->
                        <button onclick="react('movie', <?= $movie['id'] ?>, 'dislike')"
                                class="p-3 rounded-full bg-gray-600/50 hover:bg-red-600 transition <?= $userReaction === 'dislike' ? 'bg-red-600' : '' ?>">
                            <svg class="w-5 h-5 rotate-180" fill="<?= $userReaction === 'dislike' ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Películas relacionadas -->
<?php if (!empty($relatedMovies)): ?>
<div class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6">Películas Similares</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php foreach (array_slice($relatedMovies, 0, 6) as $movie): ?>
            <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<script>
function toggleList(type, id) {
    fetch('<?= url('api/list/toggle') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({type, id})
    }).then(r => r.json()).then(data => {
        location.reload();
    });
}

function react(type, id, reaction) {
    fetch('<?= url('api/react') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({type, id, reaction})
    }).then(r => r.json()).then(data => {
        location.reload();
    });
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
