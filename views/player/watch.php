<?php
/**
 * Vista: Reproductor de Video Premium
 * Con sistema de progreso (Continuar viendo)
 */
$pageTitle = $content['title'] . ' - Reproduciendo';
$videoUrl = url('media/' . ($content['video_path'] ?? ''));

// Datos para el sistema de progreso
$progressType = $contentType === 'episode' ? 'episode' : 'movie';
$progressId = $content['id'];
$initialProgress = $watchProgress['progress_seconds'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * { box-sizing: border-box; }
        body { 
            background: #000; 
            overflow: hidden; 
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        /* Ocultar controles nativos */
        video::-webkit-media-controls { display: none !important; }
        video::-webkit-media-controls-enclosure { display: none !important; }
        
        /* Barra de progreso custom */
        .progress-bar {
            -webkit-appearance: none;
            appearance: none;
            height: 5px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            cursor: pointer;
            transition: height 0.15s ease;
        }
        .progress-bar:hover { height: 8px; }
        .progress-bar::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: #e50914;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(229,9,20,0.5);
            opacity: 0;
            transition: opacity 0.15s ease;
        }
        .progress-bar:hover::-webkit-slider-thumb { opacity: 1; }
        .progress-bar::-webkit-slider-runnable-track {
            background: linear-gradient(to right, #e50914 var(--progress), rgba(255,255,255,0.2) var(--progress));
            border-radius: 5px;
        }
        
        /* Volumen */
        .volume-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 80px;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
            cursor: pointer;
        }
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            cursor: pointer;
        }
        
        /* Animaciones */
        .fade-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .controls-visible { opacity: 1 !important; }
        
        /* Loading spinner */
        .spinner {
            border: 3px solid rgba(255,255,255,0.1);
            border-top-color: #e50914;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* Fullscreen */
        :fullscreen { background: black; }
        :-webkit-full-screen { background: black; }
    </style>
</head>
<body class="bg-black text-white">
    
    <!-- Contenedor principal -->
    <div id="playerContainer" class="relative w-screen h-screen bg-black">
        
        <!-- Video -->
        <video 
            id="videoPlayer"
            class="w-full h-full object-contain"
            preload="auto"
            playsinline
        >
            <source src="<?= e($videoUrl) ?>" type="video/mp4">
            Tu navegador no soporta la reproducción de video.
        </video>
        
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="absolute inset-0 flex items-center justify-center bg-black/80 z-20">
            <div class="text-center">
                <div class="spinner mx-auto mb-4"></div>
                <p class="text-gray-400 text-sm">Cargando...</p>
            </div>
        </div>
        
        <!-- Play/Pause Overlay (click central) -->
        <div id="playOverlay" class="absolute inset-0 z-10 cursor-pointer" onclick="togglePlay()"></div>
        
        <!-- Pause Indicator -->
        <div id="pauseIndicator" class="absolute inset-0 flex items-center justify-center pointer-events-none z-15 opacity-0 transition-opacity">
            <div class="w-20 h-20 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center">
                <svg id="pauseIcon" class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
        
        <!-- Header -->
        <div id="playerHeader" class="absolute top-0 left-0 right-0 z-30 bg-gradient-to-b from-black/90 via-black/50 to-transparent pt-4 pb-16 px-4 md:px-8 opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-between max-w-screen-2xl mx-auto">
                <a href="javascript:history.back()" class="flex items-center gap-2 md:gap-3 text-white/90 hover:text-white transition group">
                    <div class="w-10 h-10 rounded-full bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span class="hidden md:inline text-sm font-medium">Volver</span>
                </a>
                
                <div class="text-center flex-1 px-4">
                    <h1 class="font-semibold text-sm md:text-lg truncate"><?= e($content['title']) ?></h1>
                    <?php if ($contentType === 'episode'): ?>
                    <p class="text-xs md:text-sm text-gray-400 mt-0.5">
                        <?= e($content['series_title'] ?? '') ?> · T<?= $content['season'] ?>:E<?= $content['episode_number'] ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <div class="w-10"></div>
            </div>
        </div>
        
        <!-- Controls Footer -->
        <div id="playerControls" class="absolute bottom-0 left-0 right-0 z-30 bg-gradient-to-t from-black/95 via-black/70 to-transparent pt-20 pb-6 px-4 md:px-8 opacity-0 transition-opacity duration-300">
            <div class="max-w-screen-2xl mx-auto space-y-3">
                
                <!-- Barra de progreso -->
                <div class="flex items-center gap-3">
                    <span id="currentTime" class="text-xs md:text-sm font-medium tabular-nums w-12 md:w-14 text-right">0:00</span>
                    <div class="flex-1 relative group">
                        <input type="range" id="progressBar" class="progress-bar w-full" min="0" max="100" value="0" style="--progress: 0%">
                        <!-- Preview thumbnail (opcional) -->
                        <div id="previewTime" class="absolute -top-8 bg-black/80 px-2 py-1 rounded text-xs font-medium hidden"></div>
                    </div>
                    <span id="duration" class="text-xs md:text-sm font-medium tabular-nums w-12 md:w-14">0:00</span>
                </div>
                
                <!-- Controles principales -->
                <div class="flex items-center justify-between">
                    
                    <!-- Izquierda: Play, Skip, Volumen -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <button onclick="togglePlay()" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white hover:scale-105 flex items-center justify-center transition transform">
                            <svg id="playPauseBtn" class="w-5 h-5 md:w-6 md:h-6 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                        
                        <button onclick="skip(-10)" class="hidden md:flex w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 items-center justify-center transition" title="Retroceder 10s">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z"/>
                            </svg>
                        </button>
                        
                        <button onclick="skip(10)" class="hidden md:flex w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 items-center justify-center transition" title="Adelantar 10s">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"/>
                            </svg>
                        </button>
                        
                        <!-- Volumen -->
                        <div class="hidden md:flex items-center gap-2 group">
                            <button onclick="toggleMute()" class="w-8 h-8 flex items-center justify-center">
                                <svg id="volumeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                </svg>
                            </button>
                            <input type="range" id="volumeSlider" class="volume-slider opacity-0 group-hover:opacity-100 transition-opacity" min="0" max="100" value="100">
                        </div>
                    </div>
                    
                    <!-- Derecha: Opciones -->
                    <div class="flex items-center gap-2 md:gap-3">
                        <button onclick="toggleFullscreen()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="Pantalla completa">
                            <svg id="fullscreenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Siguiente episodio -->
        <?php if ($nextContent): ?>
        <div id="nextEpisode" class="absolute bottom-24 right-4 md:right-8 z-40 hidden fade-in">
            <div class="bg-[#1a1a1a]/95 backdrop-blur-md rounded-xl p-4 md:p-5 shadow-2xl border border-white/10 max-w-xs">
                <p class="text-xs md:text-sm text-gray-400 mb-2">Siguiente episodio</p>
                <h3 class="font-semibold text-sm md:text-base mb-3"><?= e($nextContent['title']) ?></h3>
                <a href="<?= url('watch/episode/' . $nextContent['id']) ?>" 
                   class="flex items-center justify-center gap-2 w-full bg-white text-black py-2.5 rounded-lg font-semibold hover:bg-gray-100 transition text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Reproducir
                </a>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
    
    <script>
        // Elementos
        const video = document.getElementById('videoPlayer');
        const progressBar = document.getElementById('progressBar');
        const currentTimeEl = document.getElementById('currentTime');
        const durationEl = document.getElementById('duration');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const pauseIcon = document.getElementById('pauseIcon');
        const pauseIndicator = document.getElementById('pauseIndicator');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const playerHeader = document.getElementById('playerHeader');
        const playerControls = document.getElementById('playerControls');
        const volumeSlider = document.getElementById('volumeSlider');
        const volumeIcon = document.getElementById('volumeIcon');
        const fullscreenIcon = document.getElementById('fullscreenIcon');
        
        // Config
        const progressType = '<?= $progressType ?>';
        const progressId = <?= $progressId ?>;
        const initialProgress = <?= $initialProgress ?>;
        const isLoggedIn = <?= json_encode(isAuthenticated()) ?>;
        
        let hideTimeout;
        let lastSavedTime = 0;
        
        // Formatear tiempo
        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            if (h > 0) return `${h}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            return `${m}:${s.toString().padStart(2,'0')}`;
        }
        
        // Toggle Play/Pause
        function togglePlay() {
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }
        
        // Skip
        function skip(seconds) {
            video.currentTime = Math.max(0, Math.min(video.duration, video.currentTime + seconds));
        }
        
        // Mute
        function toggleMute() {
            video.muted = !video.muted;
            updateVolumeIcon();
        }
        
        // Fullscreen
        function toggleFullscreen() {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                document.getElementById('playerContainer').requestFullscreen();
            }
        }
        
        // Update UI
        function updatePlayPauseUI() {
            if (video.paused) {
                playPauseBtn.innerHTML = '<path d="M8 5v14l11-7z"/>';
                pauseIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
            } else {
                playPauseBtn.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';
                pauseIcon.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';
            }
        }
        
        function updateVolumeIcon() {
            if (video.muted || video.volume === 0) {
                volumeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>';
            } else {
                volumeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>';
            }
        }
        
        // Show/Hide controls
        function showControls() {
            playerHeader.classList.add('controls-visible');
            playerControls.classList.add('controls-visible');
            clearTimeout(hideTimeout);
            if (!video.paused) {
                hideTimeout = setTimeout(hideControls, 3000);
            }
        }
        
        function hideControls() {
            if (!video.paused) {
                playerHeader.classList.remove('controls-visible');
                playerControls.classList.remove('controls-visible');
            }
        }
        
        // Progress save
        function saveProgress(progress, duration) {
            if (!isLoggedIn) return;
            fetch('/api/progress/save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ type: progressType, id: progressId, progress: progress, duration: duration })
            }).catch(e => console.log('Error guardando progreso:', e));
        }
        
        // Events
        video.addEventListener('loadedmetadata', () => {
            durationEl.textContent = formatTime(video.duration);
            progressBar.max = Math.floor(video.duration);
            loadingOverlay.classList.add('hidden');
            
            // Cargar posición inicial
            if (initialProgress > 10) {
                video.currentTime = Math.max(0, initialProgress - 5);
            }
            
            showControls();
        });
        
        video.addEventListener('timeupdate', () => {
            const current = Math.floor(video.currentTime);
            const duration = Math.floor(video.duration) || 1;
            const percent = (current / duration) * 100;
            
            currentTimeEl.textContent = formatTime(current);
            progressBar.value = current;
            progressBar.style.setProperty('--progress', percent + '%');
            
            // Guardar progreso cada 30 segundos
            if (isLoggedIn && current - lastSavedTime >= 30 && current > 10) {
                lastSavedTime = current;
                saveProgress(current, duration);
            }
        });
        
        video.addEventListener('play', () => {
            updatePlayPauseUI();
            pauseIndicator.style.opacity = '0';
            hideTimeout = setTimeout(hideControls, 3000);
        });
        
        video.addEventListener('pause', () => {
            updatePlayPauseUI();
            pauseIndicator.style.opacity = '1';
            showControls();
            
            // Guardar al pausar
            if (isLoggedIn && video.currentTime > 10) {
                saveProgress(Math.floor(video.currentTime), Math.floor(video.duration));
            }
        });
        
        video.addEventListener('waiting', () => loadingOverlay.classList.remove('hidden'));
        video.addEventListener('canplay', () => loadingOverlay.classList.add('hidden'));
        
        video.addEventListener('ended', () => {
            if (isLoggedIn) {
                saveProgress(Math.floor(video.duration), Math.floor(video.duration));
            }
            <?php if ($nextContent): ?>
            document.getElementById('nextEpisode')?.classList.remove('hidden');
            showControls();
            <?php endif; ?>
        });
        
        // Progress bar input
        progressBar.addEventListener('input', () => {
            video.currentTime = progressBar.value;
        });
        
        // Volume
        volumeSlider.addEventListener('input', () => {
            video.volume = volumeSlider.value / 100;
            video.muted = false;
            updateVolumeIcon();
        });
        
        // Mouse movement
        document.addEventListener('mousemove', showControls);
        document.addEventListener('touchstart', showControls);
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            switch(e.key) {
                case ' ':
                case 'k': togglePlay(); e.preventDefault(); break;
                case 'ArrowLeft': skip(-10); break;
                case 'ArrowRight': skip(10); break;
                case 'ArrowUp': video.volume = Math.min(1, video.volume + 0.1); volumeSlider.value = video.volume * 100; e.preventDefault(); break;
                case 'ArrowDown': video.volume = Math.max(0, video.volume - 0.1); volumeSlider.value = video.volume * 100; e.preventDefault(); break;
                case 'f': toggleFullscreen(); break;
                case 'm': toggleMute(); break;
                case 'Escape': if (document.fullscreenElement) document.exitFullscreen(); break;
            }
            showControls();
        });
        
        // Fullscreen change
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            } else {
                fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>';
            }
        });
        
        // Save on page leave
        window.addEventListener('beforeunload', () => {
            if (isLoggedIn && video.currentTime > 10) {
                navigator.sendBeacon('/api/progress/save', JSON.stringify({
                    type: progressType,
                    id: progressId,
                    progress: Math.floor(video.currentTime),
                    duration: Math.floor(video.duration)
                }));
            }
        });
        
        // Auto-play con sonido desactivado inicialmente
        video.muted = false;
    </script>
</body>
</html>


