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
        
        /* Barra de progreso Premium */
        .progress-bar {
            -webkit-appearance: none;
            appearance: none;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            cursor: pointer;
            transition: height 0.2s ease;
        }
        .progress-group:hover .progress-bar { height: 6px; }
        .progress-bar::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            background: #e50914;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            transform: scale(0);
            transition: transform 0.2s ease;
        }
        .progress-group:hover .progress-bar::-webkit-slider-thumb { transform: scale(1); }
        .progress-bar::-webkit-slider-runnable-track {
            background: linear-gradient(to right, #e50914 var(--progress), rgba(255,255,255,0.3) var(--progress));
            border-radius: 2px;
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
            transition: width 0.1s ease;
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
            autoplay
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
                </a>
                
                <div class="text-center flex-1 px-4">
                    <h1 class="font-semibold text-sm md:text-lg truncate"><?= e($content['title']) ?></h1>
                    <?php if ($contentType === 'episode'): ?>
                    <p class="text-xs md:text-sm text-gray-400 mt-0.5">
                        <?= e($content['series_title'] ?? '') ?> · T<?= $content['season'] ?>:E<?= $content['episode_number'] ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <!-- Right Header Controls: Episodes & Quality -->
                <div class="flex items-center gap-3">
                    <?php if ($contentType === 'episode'): ?>
                    <button onclick="toggleEpisodes()" class="text-gray-300 hover:text-white transition p-2 rounded-full hover:bg-white/10" title="Lista de Episodios">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <?php endif; ?>

                    <div id="qualityBadge" class="hidden md:flex px-2 py-0.5 rounded bg-white/10 text-[10px] sm:text-xs font-bold text-gray-300 border border-white/5 select-none tracking-wide"></div>
                </div>
            </div>
        </div>
        
        <!-- Controls Footer (Mejorado Mobile) -->
        <div id="playerControls" class="absolute bottom-0 left-0 right-0 z-30 bg-gradient-to-t from-black via-black/80 to-transparent pt-32 pb-20 md:pb-8 px-4 md:px-12 opacity-0 transition-opacity duration-300">
            <div class="max-w-screen-2xl mx-auto space-y-2 md:space-y-4">
                
                <!-- Barra de progreso -->
                <div class="flex items-center gap-3 progress-group group relative">
                    <span id="currentTime" class="text-xs md:text-sm font-medium tabular-nums w-10 md:w-14 text-right text-gray-300">0:00</span>
                    <div class="flex-1 relative flex items-center h-4 cursor-pointer group-hover:h-6 transition-all">
                        <input type="range" id="progressBar" class="progress-bar w-full absolute inset-0 z-20 opacity-0 cursor-pointer" min="0" max="100" step="0.1" value="0">
                        <div class="w-full h-1 bg-white/30 rounded-full overflow-hidden relative pointer-events-none">
                            <div id="progressFill" class="h-full bg-[#e50914] w-0 relative">
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-[#e50914] rounded-full shadow-lg scale-0 group-hover:scale-100 transition-transform"></div>
                            </div>
                        </div>
                        <div id="previewTime" class="absolute -top-10 bg-black/80 px-2 py-1 rounded text-xs font-medium hidden border border-white/10 transform -translate-x-1/2"></div>
                    </div>
                    <span id="duration" class="text-xs md:text-sm font-medium tabular-nums w-10 md:w-14 text-gray-300">0:00</span>
                </div>
                
                <!-- Controles principales -->
                <div class="flex items-center justify-between">
                    
                    <!-- Izquierda: Play, Next, Skip, Vol -->
                    <div class="flex items-center gap-4 md:gap-6">
                        <button onclick="togglePlay()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-white" title="Reproducir/Pausar">
                            <svg id="playPauseBtn" class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                        
                        <?php if ($nextContent): ?>
                        <a href="<?= url('watch/episode/' . $nextContent['id']) ?>" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="Siguiente: <?= e($nextContent['title']) ?>">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M5.536 21.886a1.004 1.004 0 0 0 1.033-.064l13-9a1 1 0 0 0 0-1.644l-13-9A1 1 0 0 0 5 3v18a1 1 0 0 0 .536.886zM19 3v18h2V3h-2z"/></svg>
                        </a>
                        <?php endif; ?>

                        <div class="hidden md:flex items-center gap-4">
                            <button onclick="skip(-10)" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="-10s">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z"/></svg>
                            </button>
                            <button onclick="skip(10)" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="+10s">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"/></svg>
                            </button>
                        </div>
                        
                        <!-- Volumen -->
                        <div class="hidden md:flex items-center gap-2 group">
                            <button onclick="toggleMute()" class="p-2">
                                <svg id="volumeIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                            </button>
                            <input type="range" id="volumeSlider" class="volume-slider opacity-0 group-hover:opacity-100 transition-opacity" min="0" max="100" value="100">
                        </div>
                    </div>
                    
                    <!-- Derecha: Fullscreen -->
                    <div class="flex items-center gap-3">
                        <button onclick="toggleFullscreen()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="Pantalla completa">
                            <svg id="fullscreenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Siguiente episodio (Popup clásico) -->
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

        <!-- Drawer de Episodios -->
        <div id="episodesDrawer" class="absolute top-0 right-0 bottom-0 w-80 max-w-[85vw] bg-[#141414]/95 backdrop-blur-xl border-l border-white/10 transform translate-x-full transition-transform duration-300 z-50 flex flex-col shadow-2xl">
            <div class="p-5 border-b border-white/10 flex justify-between items-center bg-black/20">
                <h3 class="font-bold text-base text-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Episodios
                </h3>
                <button onclick="toggleEpisodes()" class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="episodesList" class="flex-1 overflow-y-auto p-4 space-y-1 scrollbar-thin">
                <div class="text-center py-10 text-gray-500 animate-pulse">Cargando lista...</div>
            </div>
        </div>
        
    </div>
    
    <script>
        // Elementos
        const video = document.getElementById('videoPlayer');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');
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
        const qualityBadge = document.getElementById('qualityBadge');
        const episodesDrawer = document.getElementById('episodesDrawer');
        const seriesId = <?= $contentType === 'episode' ? ($content['series_id'] ?? 'null') : 'null' ?>;

        // Toggle Drawer
        function toggleEpisodes() {
            if (!episodesDrawer) return;
            if (episodesDrawer.classList.contains('translate-x-full')) {
                episodesDrawer.classList.remove('translate-x-full');
                video.pause();
                loadEpisodes();
            } else {
                episodesDrawer.classList.add('translate-x-full');
            }
        }
        
        let loadedSeriesId = null;
        function loadEpisodes() {
            if (loadedSeriesId === seriesId || !seriesId) return;
            
            fetch('/api/episodes/' + seriesId)
                .then(r => r.json())
                .then(data => {
                    if (data.episodes && data.episodes.length) {
                        let html = '';
                        let currentSeason = null;
                        data.episodes.forEach(ep => {
                            if (ep.season != currentSeason) {
                                currentSeason = ep.season;
                                html += `<h4 class="font-bold text-gray-400 mt-4 mb-2 text-xs uppercase tracking-wider sticky top-0 bg-[#141414] py-1 z-10 w-full pl-1">Temporada ${ep.season}</h4>`;
                            }
                            const isActive = ep.id == <?= $content['id'] ?>;
                            const activeClass = isActive ? 'bg-white/10 border-red-600' : 'hover:bg-white/5 border-transparent';
                            html += `
                                <a href="/watch/series/${seriesId}/${ep.id}" class="block p-3 rounded mb-1 transition border-l-4 ${activeClass}">
                                    <div class="flex items-start gap-3">
                                        <div class="font-bold text-sm text-gray-500 w-5 mt-0.5">${ep.episode_number}</div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-white mb-0.5 truncate">${ep.title}</div>
                                            <div class="text-xs text-gray-400 truncate">${ep.description || 'Sin descripción'}</div>
                                        </div>
                                    </div>
                                </a>`;
                        });
                        document.getElementById('episodesList').innerHTML = html;
                        loadedSeriesId = seriesId;
                    }
                });
        }

        // Detectar resolución real
        video.addEventListener('loadedmetadata', () => {
            const h = video.videoHeight;
            const w = video.videoWidth;
            let label = 'SD';
            
            if (h >= 2160 || w >= 3840) label = '4K UHD';
            else if (h >= 1440 || w >= 2560) label = '2K QHD';
            else if (h >= 1080 || w >= 1920) label = '1080p FHD';
            else if (h >= 720 || w >= 1280) label = '720p HD';
            
            if (qualityBadge) {
                qualityBadge.textContent = label;
                qualityBadge.classList.remove('hidden');
                qualityBadge.classList.add('animate-fadeIn'); // Efecto visual
            }
            console.log(`Video resolution detected: ${w}x${h} (${label})`);
            
            // Standard metadata Actions
            durationEl.textContent = formatTime(video.duration);
            progressBar.max = Math.floor(video.duration);
            loadingOverlay.classList.add('hidden');
            
            // Cargar posición inicial
            if (initialProgress > 10) {
                video.currentTime = Math.max(0, initialProgress - 5);
            }
            
            showControls();
        });
        
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
        // loadedmetadata (Already defined above)
        
        video.addEventListener('timeupdate', () => {
            const current = Math.floor(video.currentTime);
            const duration = Math.floor(video.duration) || 1;
            const percent = (current / duration) * 100;
            
            currentTimeEl.textContent = formatTime(current);
            progressBar.value = current;
            // UPDATE: Usar progressFill en lugar de --progress variable
            if(progressFill) progressFill.style.width = percent + '%';
            
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
            const val = progressBar.value;
            video.currentTime = val; // Seek immediately
            const percent = (val / video.duration) * 100;
            if(progressFill) progressFill.style.width = percent + '%';
            currentTimeEl.textContent = formatTime(val);
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
                // Icono contraer
                fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5-5m0 0l5-5m-5 5h14m-14-10l5 5m-5-5l5-5m-5 5h14"/>'; // Fallback path if needed, but let's use a standard "Exit Fullscreen" or "Contract"
                 fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H4v6m6-6v6m0-6H4m10 0h6v6m-6-6v6m0-6h6M14 10h6V4m-6 6V4m0 6h6M10 10H4V4m6 6V4m0 6H4"/>'; // Complex grid
                 fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'; // Cross (User hated this)
                 // Correct Contract Icon (Arrows pointing in)
                 fullscreenIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 16h6m0 0v6m14-6h-6m0 0v6M19 8h-6m0 0V2M5 8h6m0 0V2"/>';
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
