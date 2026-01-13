<?php
/**
 * Vista: Reproductor de Video
 */
$pageTitle = $content['title'] . ' - Reproduciendo';

$videoUrl = url('media/' . ($content['video_path'] ?? ''));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet">
    
    <style>
        body { background: #000; overflow: hidden; }
        .vjs-jech .vjs-big-play-button { background: #e50914; border: none; }
        .vjs-jech .vjs-control-bar { background: linear-gradient(transparent, rgba(0,0,0,0.9)); }
        .vjs-jech .vjs-play-progress, .vjs-jech .vjs-volume-level { background: #e50914; }
    </style>
</head>
<body class="bg-black">
    
    <!-- Header del reproductor -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-b from-black/90 to-transparent p-4" id="player-header">
        <div class="flex items-center justify-between">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-white hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Volver</span>
            </a>
            
            <div class="text-center">
                <h1 class="font-semibold"><?= e($content['title']) ?></h1>
                <?php if ($contentType === 'episode'): ?>
                <p class="text-sm text-gray-400">
                    <?= e($content['series_title'] ?? '') ?> - T<?= $content['season'] ?>:E<?= $content['episode_number'] ?>
                </p>
                <?php endif; ?>
            </div>
            
            <div class="w-20"></div>
        </div>
    </div>
    
    <!-- Video Player -->
    <div class="fixed inset-0 flex items-center justify-center">
        <video 
            id="video-player"
            class="video-js vjs-jech vjs-big-play-centered w-full h-full"
            controls
            preload="auto"
            data-setup='{"fluid": true}'
        >
            <source src="<?= e($videoUrl) ?>" type="video/mp4">
            <p class="vjs-no-js">
                Para ver este video, habilita JavaScript o usa un navegador compatible.
            </p>
        </video>
    </div>
    
    <!-- Siguiente episodio (si aplica) -->
    <?php if ($nextContent): ?>
    <div class="fixed bottom-20 right-4 z-50 hidden" id="next-episode">
        <div class="bg-jf-card/95 backdrop-blur rounded-lg p-4 shadow-xl max-w-xs">
            <p class="text-sm text-gray-400 mb-2">Siguiente episodio</p>
            <h3 class="font-semibold mb-3"><?= e($nextContent['title']) ?></h3>
            <a href="<?= url('watch/episode/' . $nextContent['id']) ?>" 
               class="block w-full bg-white text-black text-center py-2 rounded font-medium hover:bg-gray-200 transition">
                Reproducir
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>
    <script>
        const player = videojs('video-player', {
            controls: true,
            autoplay: false,
            preload: 'auto',
            responsive: true,
            fill: true
        });
        
        // Ocultar header al reproducir
        let hideTimeout;
        const header = document.getElementById('player-header');
        
        player.on('play', () => {
            hideTimeout = setTimeout(() => header.classList.add('opacity-0'), 3000);
        });
        
        player.on('pause', () => {
            clearTimeout(hideTimeout);
            header.classList.remove('opacity-0');
        });
        
        document.addEventListener('mousemove', () => {
            header.classList.remove('opacity-0');
            clearTimeout(hideTimeout);
            if (!player.paused()) {
                hideTimeout = setTimeout(() => header.classList.add('opacity-0'), 3000);
            }
        });
        
        // Mostrar siguiente episodio al finalizar
        <?php if ($nextContent): ?>
        player.on('ended', () => {
            document.getElementById('next-episode')?.classList.remove('hidden');
        });
        <?php endif; ?>
    </script>
</body>
</html>
