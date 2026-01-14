<?php
/**
 * Vista: Mi Lista - Rediseñada con Modal
 */
$pageTitle = 'Mi Lista';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
        
        <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">Mi Lista</h1>
        
        <?php if (empty($items)): ?>
            <div class="text-center py-16 md:py-24">
                <!-- Icono animado -->
                <div class="w-20 h-20 md:w-24 md:h-24 mx-auto mb-6 md:mb-8 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center border border-white/5">
                    <svg class="w-10 h-10 md:w-12 md:h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
                
                <h2 class="text-xl md:text-2xl font-bold text-gray-200 mb-3">Tu lista está vacía</h2>
                <p class="text-gray-500 mb-8 md:mb-10 max-w-md mx-auto text-sm md:text-base">
                    Guarda películas y series para verlas más tarde. Solo haz clic en el ícono de guardar en cualquier contenido.
                </p>
                
                <!-- Botones de explorar -->
                <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">
                    <a href="<?= url('movies') ?>" class="inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 md:px-8 py-3 md:py-4 rounded-xl font-semibold transition shadow-lg shadow-red-500/20 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        Explorar Películas
                    </a>
                    <a href="<?= url('series') ?>" class="inline-flex items-center gap-2 md:gap-3 bg-white/5 hover:bg-white/10 border border-white/10 px-6 md:px-8 py-3 md:py-4 rounded-xl font-semibold transition text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Explorar Series
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
                <?php foreach ($items as $item): 
                    $isMovie = $item['content_type'] === 'movie';
                    $playUrl = $isMovie ? url('watch/movie/' . $item['content_id']) : url('series/' . $item['content_id']);
                    $itemData = json_encode([
                        'id' => $item['content_id'],
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'poster' => $item['poster'] ?? '',
                        'backdrop' => $item['backdrop'] ?? $item['poster'] ?? '',
                        'year' => $item['year'] ?? $item['year_start'] ?? null,
                        'duration' => $item['duration'] ?? null,
                        'total_seasons' => $item['total_seasons'] ?? null,
                        'categories' => $item['categories'] ?? '',
                        'video_path' => $isMovie ? ($item['video_path'] ?? '') : null
                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                    // Series van al detalle, películas al reproductor
                    $playUrl = $isMovie ? url('watch/movie/' . $item['content_id']) : url('series/' . $item['content_id']);
                ?>
                <div class="group">
                    <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-[#161616] cursor-pointer" onclick='openInfoModal(<?= $itemData ?>)'>
                        <img src="<?= posterUrl($item['poster']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                        
                        <!-- Badge de tipo -->
                        <div class="absolute top-2 left-2 md:top-3 md:left-3 px-2 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[10px] md:text-xs font-bold uppercase <?= $isMovie ? 'bg-red-600' : 'bg-purple-600' ?>">
                            <?= $isMovie ? 'Película' : 'Serie' ?>
                        </div>
                        
                        <!-- Overlay hover con gradiente -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Botón Play central -->
                        <a href="<?= $playUrl ?>" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 z-10" onclick="event.stopPropagation()">
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white/95 hover:bg-white flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-xl">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </a>
                        
                        <!-- Título en hover (sin duplicar debajo) -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 md:p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-5">
                            <h3 class="font-bold text-xs md:text-sm line-clamp-2"><?= e($item['title']) ?></h3>
                            <?php if (!empty($item['year']) || !empty($item['year_start'])): ?>
                            <span class="text-[10px] md:text-xs text-gray-400"><?= e($item['year'] ?? $item['year_start']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<!-- Info Modal -->
<div id="infoModal" class="fixed inset-0 z-50 hidden">
    <!-- Overlay visual -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm pointer-events-none"></div>
    
    <!-- Modal Container - solo scroll interno -->
    <div class="absolute inset-0 flex items-end sm:items-center justify-center sm:p-6 md:p-8" onclick="closeInfoModal()">
        <div class="relative bg-[#141414] rounded-t-3xl sm:rounded-2xl max-w-4xl w-full sm:my-8 shadow-2xl border-t sm:border border-white/10 max-h-[95vh] sm:max-h-[85vh] overflow-y-auto overflow-x-hidden scrollbar-thin" onclick="event.stopPropagation()">
            
            <!-- Modal Header with Backdrop -->
            <div class="relative h-36 sm:h-56 md:h-72">
                <img id="modalBackdrop" src="" alt="" class="w-full h-full object-cover rounded-t-3xl sm:rounded-t-2xl">
                <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-[#141414]/50 to-transparent rounded-t-3xl sm:rounded-t-2xl"></div>
                <button onclick="closeInfoModal()" class="absolute top-3 right-3 md:top-4 md:right-4 w-9 h-9 md:w-10 md:h-10 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center hover:bg-black/70 transition z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 sm:p-6 md:p-8 -mt-10 sm:-mt-12 md:-mt-16 relative">
                <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-6">
                    <!-- Poster Thumbnail -->
                    <img id="modalPoster" src="" alt="" class="w-20 h-28 sm:w-28 sm:h-40 md:w-32 md:h-48 object-cover rounded-xl shadow-2xl flex-shrink-0 mx-auto sm:mx-0">
                    
                    <div class="flex-1 text-center sm:text-left w-full">
                        <!-- Badge -->
                        <span id="modalBadge" class="inline-block px-3 py-1 bg-red-600 rounded-lg text-xs font-bold uppercase mb-2 md:mb-3">Película</span>
                        
                        <!-- Title -->
                        <h2 id="modalTitle" class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3 md:mb-4"></h2>
                        
                        <!-- Meta -->
                        <div id="modalMeta" class="flex flex-wrap justify-center sm:justify-start items-center gap-2 sm:gap-3 md:gap-4 text-gray-300 text-xs sm:text-sm md:text-base mb-2 sm:mb-3 md:mb-4"></div>
                        
                        <!-- Categories -->
                        <div id="modalCategories" class="flex flex-wrap justify-center sm:justify-start gap-1.5 sm:gap-2 mb-3 sm:mb-4 md:mb-6"></div>
                        
                        <!-- Description -->
                        <p id="modalDescription" class="text-gray-300 text-xs sm:text-sm md:text-base leading-relaxed mb-4 md:mb-6 line-clamp-4 sm:line-clamp-none"></p>
                        
                        <!-- Actions - Layout diferente en mobile -->
                        <div class="space-y-3 sm:space-y-0">
                            <!-- Fila principal: Play y Mi Lista -->
                            <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2 md:gap-3">
                                <a id="modalPlayBtn2" href="#" class="inline-flex items-center gap-2 bg-white text-black px-4 md:px-5 py-2 md:py-2.5 rounded-xl font-bold hover:bg-gray-100 transition text-sm md:text-base">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Reproducir
                                </a>
                                <button id="modalAddListBtn" onclick="toggleModalList()" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-3 md:px-4 py-2 md:py-2.5 rounded-xl font-medium transition text-sm md:text-base">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Mi Lista
                                </button>
                                <!-- Like/Dislike en desktop inline -->
                                <div class="hidden sm:flex items-center gap-2">
                                    <button id="modalLikeBtn" onclick="modalReact('like')" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-gray-400" title="Me gusta">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                    </button>
                                    <button id="modalDislikeBtn" onclick="modalReact('dislike')" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-gray-400" title="No me gusta">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/></svg>
                                    </button>
                                </div>
                            </div>
                            <!-- Like/Dislike en mobile (fila separada) -->
                            <div class="flex sm:hidden justify-center gap-3">
                                <button id="modalLikeBtnMobile" onclick="modalReact('like')" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-gray-400" title="Me gusta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                </button>
                                <button id="modalDislikeBtnMobile" onclick="modalReact('dislike')" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-gray-400" title="No me gusta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/></svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Episodios (solo para series) -->
                        <div id="modalEpisodes" class="mt-4 md:mt-6 hidden">
                            <!-- Header con título y selector de temporada dropdown -->
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h3 class="text-sm sm:text-base md:text-lg font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Episodios
                                </h3>
                                <!-- Dropdown selector de temporada -->
                                <select id="seasonSelector" onchange="selectSeasonDropdown(this.value)" class="bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer focus:outline-none focus:border-purple-500">
                                </select>
                            </div>
                            <!-- Lista de episodios -->
                            <div id="modalEpisodesList" class="space-y-2 max-h-52 sm:max-h-64 overflow-y-auto pr-2 scrollbar-thin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Script -->
<script>
let currentModalItem = null;
const isLoggedIn = <?= json_encode(isAuthenticated()) ?>;
const loginUrl = '<?= url('login') ?>';

function openInfoModal(item) {
    currentModalItem = item;
    const modal = document.getElementById('infoModal');
    if (!modal) return;
    
    const isMovie = item.video_path !== undefined && item.video_path !== null;
    
    // Imágenes
    const defaultImg = '/assets/images/default-poster.svg';
    const backdrop = item.backdrop ? '/' + item.backdrop : (item.poster ? '/' + item.poster : defaultImg);
    const poster = item.poster ? '/' + item.poster : defaultImg;
    document.getElementById('modalBackdrop').src = backdrop;
    document.getElementById('modalPoster').src = poster;
    
    // Badge
    const badge = document.getElementById('modalBadge');
    badge.textContent = isMovie ? 'Película' : 'Serie';
    badge.className = 'inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase mb-2 md:mb-3 ' + (isMovie ? 'bg-red-600' : 'bg-purple-600');
    
    // Título y descripción
    document.getElementById('modalTitle').textContent = item.title;
    document.getElementById('modalDescription').textContent = item.description || 'Sin descripción disponible.';
    
    // Meta info
    let meta = '';
    if (item.year) {
        meta += '<span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + item.year + '</span>';
    }
    if (item.duration) {
        const h = Math.floor(item.duration / 60);
        const m = item.duration % 60;
        meta += '<span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' + (h > 0 ? h + 'h ' : '') + m + 'min</span>';
    }
    if (item.total_seasons) {
        meta += '<span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>' + item.total_seasons + ' Temp.</span>';
    }
    document.getElementById('modalMeta').innerHTML = meta;
    
    // Categorías
    let cats = '';
    if (item.categories) {
        item.categories.split(', ').forEach(c => cats += '<span class="px-3 py-1 bg-white/10 rounded-full text-sm">' + c + '</span>');
    }
    document.getElementById('modalCategories').innerHTML = cats;
    
    // URL de reproducir
    const playUrl = isMovie ? '/watch/movie/' + item.id : '/series/' + item.id;
    document.getElementById('modalPlayBtn2').href = playUrl;
    
    // Sección de episodios con selector de temporadas
    const episodesDiv = document.getElementById('modalEpisodes');
    const episodesList = document.getElementById('modalEpisodesList');
    const seasonSelector = document.getElementById('seasonSelector');
    
    if (!isMovie && episodesDiv && episodesList) {
        // Cargar episodios via API para series
        episodesDiv.classList.remove('hidden');
        if (seasonSelector) seasonSelector.innerHTML = '';
        episodesList.innerHTML = '<div class="text-center py-4 text-gray-500"><svg class="w-5 h-5 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>';
        
        fetch('/api/episodes/' + item.id)
            .then(r => r.json())
            .then(data => {
                if (data.episodes && data.episodes.length > 0) {
                    // Agrupar episodios por temporada
                    const seasons = {};
                    data.episodes.forEach(ep => {
                        if (!seasons[ep.season]) seasons[ep.season] = [];
                        seasons[ep.season].push(ep);
                    });
                    
                    const seasonNumbers = Object.keys(seasons).sort((a, b) => a - b);
                    
                    // Guardar datos para uso en dropdown
                    window.currentSeasons = seasons;
                    window.currentSeriesId = item.id;
                    
                    // Crear opciones del dropdown
                    if (seasonSelector) {
                        seasonNumbers.forEach((sNum, idx) => {
                            const option = document.createElement('option');
                            option.value = sNum;
                            option.textContent = 'Temporada ' + sNum;
                            if (idx === 0) option.selected = true;
                            seasonSelector.appendChild(option);
                        });
                    }
                    
                    // Mostrar episodios de la primera temporada
                    if (seasonNumbers.length > 0) {
                        renderEpisodesList(seasons[seasonNumbers[0]], item.id);
                    }
                } else {
                    if (seasonSelector) seasonSelector.style.display = 'none';
                    episodesList.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">No hay episodios disponibles</div>';
                }
            })
            .catch(e => {
                episodesList.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Error al cargar episodios</div>';
            });
    } else if (episodesDiv) {
        episodesDiv.classList.add('hidden');
        if (seasonSelector) seasonSelector.innerHTML = '';
    }
    
    // Mostrar modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Función para cambiar temporada desde dropdown (Mi Lista)
function selectSeasonDropdown(season) {
    if (window.currentSeasons && window.currentSeriesId) {
        renderEpisodesList(window.currentSeasons[season], window.currentSeriesId);
    }
}

// Renderizar episodios (Mi Lista)
function renderEpisodesList(episodes, seriesId) {
    const list = document.getElementById('modalEpisodesList');
    if (!episodes || episodes.length === 0) {
        list.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">No hay episodios en esta temporada</div>';
        return;
    }
    
    let html = '';
    episodes.forEach(ep => {
        html += '<a href="/watch/series/' + seriesId + '/' + ep.id + '" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-purple-500/10 hover:border-purple-500/30 border border-transparent transition group">';
        html += '<span class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold shrink-0">' + ep.episode_number + '</span>';
        html += '<div class="flex-1 min-w-0">';
        html += '<div class="font-medium text-sm truncate group-hover:text-purple-300 transition">' + (ep.title || 'Episodio ' + ep.episode_number) + '</div>';
        if (ep.duration) {
            html += '<div class="text-xs text-gray-500">' + ep.duration + ' min</div>';
        }
        if (ep.description) {
            html += '<div class="text-xs text-gray-600 truncate mt-0.5">' + ep.description + '</div>';
        }
        html += '</div>';
        html += '<svg class="w-5 h-5 text-gray-600 group-hover:text-purple-400 shrink-0 transition" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
        html += '</a>';
    });
    list.innerHTML = html;
}

function closeInfoModal() {
    document.getElementById('infoModal')?.classList.add('hidden');
    document.body.style.overflow = '';
    currentModalItem = null;
}

function toggleModalList() {
    if (!isLoggedIn) { window.location.href = loginUrl; return; }
    if (!currentModalItem) return;
    const type = currentModalItem.video_path !== undefined && currentModalItem.video_path !== null ? 'movie' : 'series';
    fetch('/api/list', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({type: type, id: currentModalItem.id})
    }).then(r => r.json()).then(d => {
        const btn = document.getElementById('modalAddListBtn');
        if (d.in_list) {
            // Solo cambia el icono a check
            btn.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> En Mi Lista';
        } else {
            btn.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Mi Lista';
        }
    }).catch(e => console.log('Error:', e));
}

function modalReact(reaction) {
    if (!isLoggedIn) { window.location.href = loginUrl; return; }
    if (!currentModalItem) return;
    const type = currentModalItem.video_path !== undefined && currentModalItem.video_path !== null ? 'movie' : 'series';
    fetch('/api/react', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({type: type, id: currentModalItem.id, reaction: reaction})
    }).then(r => r.json()).then(d => {
        // Desktop buttons
        const likeBtn = document.getElementById('modalLikeBtn');
        const dislikeBtn = document.getElementById('modalDislikeBtn');
        // Mobile buttons
        const likeBtnMobile = document.getElementById('modalLikeBtnMobile');
        const dislikeBtnMobile = document.getElementById('modalDislikeBtnMobile');
        
        // Clases base y activas
        const baseClass = 'rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-gray-400';
        const likeActiveClass = 'rounded-full bg-green-500 hover:bg-green-600 flex items-center justify-center transition text-white';
        const dislikeActiveClass = 'rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition text-white';
        
        // Actualizar botones de like
        [likeBtn, likeBtnMobile].forEach(btn => {
            if (!btn) return;
            const size = btn.id.includes('Mobile') ? 'w-10 h-10' : 'w-9 h-9 md:w-10 md:h-10';
            btn.className = size + ' ' + (d.reaction === 'like' ? likeActiveClass : baseClass);
        });
        
        // Actualizar botones de dislike
        [dislikeBtn, dislikeBtnMobile].forEach(btn => {
            if (!btn) return;
            const size = btn.id.includes('Mobile') ? 'w-10 h-10' : 'w-9 h-9 md:w-10 md:h-10';
            btn.className = size + ' ' + (d.reaction === 'dislike' ? dislikeActiveClass : baseClass);
        });
    }).catch(e => console.log('Error:', e));
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeInfoModal(); });
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>

