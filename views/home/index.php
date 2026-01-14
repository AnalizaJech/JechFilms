<?php
/**
 * Vista: Página de Inicio - Premium con Hero Creativo
 */
$pageTitle = 'Inicio';

// Iconos por categoría
$categoryIcons = [
    'accion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
    'aventura' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'comedia' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'drama' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'terror' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
    'ciencia ficcion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>',
    'romance' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'thriller' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
    'animacion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    'documental' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
];

// Colores por categoría
$categoryColors = [
    'accion' => ['from-orange-500 to-red-600', 'bg-orange-500', 'text-orange-400', 'shadow-orange-500/30'],
    'aventura' => ['from-emerald-500 to-teal-600', 'bg-emerald-500', 'text-emerald-400', 'shadow-emerald-500/30'],
    'comedia' => ['from-yellow-400 to-orange-500', 'bg-yellow-500', 'text-yellow-400', 'shadow-yellow-500/30'],
    'drama' => ['from-red-500 to-rose-600', 'bg-red-500', 'text-red-400', 'shadow-red-500/30'],
    'terror' => ['from-slate-600 to-slate-800', 'bg-slate-600', 'text-slate-400', 'shadow-slate-500/30'],
    'ciencia ficcion' => ['from-cyan-400 to-blue-600', 'bg-cyan-500', 'text-cyan-400', 'shadow-cyan-500/30'],
    'romance' => ['from-pink-500 to-rose-600', 'bg-pink-500', 'text-pink-400', 'shadow-pink-500/30'],
    'thriller' => ['from-violet-500 to-purple-700', 'bg-violet-500', 'text-violet-400', 'shadow-violet-500/30'],
    'animacion' => ['from-blue-500 to-indigo-600', 'bg-blue-500', 'text-blue-400', 'shadow-blue-500/30'],
    'documental' => ['from-teal-400 to-cyan-600', 'bg-teal-500', 'text-teal-400', 'shadow-teal-500/30'],
];

$defaultIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>';

ob_start();
?>

<!-- Hero Carousel Section -->
<?php if (!empty($featured)): ?>
<section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Slides Container -->
    <div id="heroCarousel" class="absolute inset-0">
        <?php foreach ($featured as $index => $item): ?>
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 <?= $index === 0 ? 'opacity-100' : 'opacity-0' ?>" data-index="<?= $index ?>">
            <img 
                src="<?= posterUrl($item['backdrop'] ?? $item['poster'] ?? null) ?>" 
                alt="<?= e($item['title']) ?>"
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0a]/90 via-[#0a0a0a]/30 to-transparent"></div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Content Overlay -->
    <div class="relative container mx-auto px-6 z-10">
        <?php foreach ($featured as $index => $item): 
            $isMovie = isset($item['video_path']);
        ?>
        <div class="hero-content max-w-2xl transition-all duration-700 <?= $index === 0 ? 'block opacity-100' : 'hidden opacity-0' ?>" data-index="<?= $index ?>">
            <span class="inline-block px-3 py-1.5 <?= $isMovie ? 'bg-red-600' : 'bg-purple-600' ?> rounded-lg text-xs font-bold uppercase tracking-wide mb-4">
                <?= $isMovie ? 'Película' : 'Serie' ?>
            </span>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-4 leading-[1.1]"><?= e($item['title']) ?></h1>
            
            <!-- Meta info -->
            <div class="flex flex-wrap items-center gap-4 text-gray-300 mb-4">
                <?php if (!empty($item['year']) || !empty($item['year_start'])): ?>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?= e($item['year'] ?? $item['year_start']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($item['duration'])): ?>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= formatDuration($item['duration']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($item['total_seasons'])): ?>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <?= $item['total_seasons'] ?> Temporadas
                </span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($item['description'])): ?>
            <p class="text-lg text-gray-300 mb-8 line-clamp-3 leading-relaxed max-w-xl"><?= e($item['description']) ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap items-center gap-4">
                <a href="<?= url($isMovie ? 'watch/movie/' . $item['id'] : 'series/' . $item['id']) ?>" 
                   class="inline-flex items-center gap-3 bg-white text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Reproducir
                </a>
                <button onclick='openInfoModal(<?= json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' 
                        class="inline-flex items-center gap-3 bg-white/15 backdrop-blur-sm px-6 py-4 rounded-xl font-semibold hover:bg-white/25 transition border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Más Info
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Navigation Arrows -->
    <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center hover:bg-black/70 transition opacity-0 hover:opacity-100 group-hover:opacity-100" id="prevBtn">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center hover:bg-black/70 transition opacity-0 hover:opacity-100 group-hover:opacity-100" id="nextBtn">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    
    <!-- Slide Indicators -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
        <?php foreach ($featured as $index => $item): ?>
        <button onclick="goToSlide(<?= $index ?>)" 
                class="hero-indicator w-2 h-2 rounded-full transition-all duration-300 <?= $index === 0 ? 'bg-white w-8' : 'bg-white/40 hover:bg-white/60' ?>" 
                data-index="<?= $index ?>"></button>
        <?php endforeach; ?>
    </div>
</section>

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

<script>
// Variable para saber si el usuario está logueado
const isLoggedIn = <?= json_encode(isAuthenticated()) ?>;
const loginUrl = '<?= url("login") ?>';
// Hero Carousel - Sin barra de progreso, usando hidden/block
let currentSlide = 0;
let currentModalItem = null;
const slides = document.querySelectorAll('.hero-slide');
const contents = document.querySelectorAll('.hero-content');
const indicators = document.querySelectorAll('.hero-indicator');
const totalSlides = slides.length;
const autoPlayInterval = 3000;
let autoPlayTimer;

function goToSlide(index) {
    if (index === currentSlide) return;
    
    // Ocultar slide actual
    slides[currentSlide].classList.remove('opacity-100');
    slides[currentSlide].classList.add('opacity-0');
    contents[currentSlide].classList.remove('block', 'opacity-100');
    contents[currentSlide].classList.add('hidden', 'opacity-0');
    indicators[currentSlide].classList.remove('bg-white', 'w-8');
    indicators[currentSlide].classList.add('bg-white/40');
    
    // Mostrar nuevo slide
    currentSlide = index;
    slides[currentSlide].classList.remove('opacity-0');
    slides[currentSlide].classList.add('opacity-100');
    contents[currentSlide].classList.remove('hidden', 'opacity-0');
    contents[currentSlide].classList.add('block', 'opacity-100');
    indicators[currentSlide].classList.remove('bg-white/40');
    indicators[currentSlide].classList.add('bg-white', 'w-8');
}

function nextSlide() {
    goToSlide((currentSlide + 1) % totalSlides);
}

function prevSlide() {
    goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
}

function startAutoPlay() {
    autoPlayTimer = setInterval(nextSlide, autoPlayInterval);
}

function stopAutoPlay() {
    clearInterval(autoPlayTimer);
}

// Pausar al hover
document.getElementById('heroCarousel')?.parentElement?.addEventListener('mouseenter', stopAutoPlay);
document.getElementById('heroCarousel')?.parentElement?.addEventListener('mouseleave', startAutoPlay);

// Iniciar autoplay
if (totalSlides > 0) {
    startAutoPlay();
}

// Info Modal
function openInfoModal(item) {
    currentModalItem = item;
    const modal = document.getElementById('infoModal');
    const isMovie = item.video_path !== undefined;
    
    // Imágenes - usar ruta directa o placeholder
    const defaultImg = '/assets/images/default-poster.svg';
    const backdrop = item.backdrop ? '/' + item.backdrop : (item.poster ? '/' + item.poster : defaultImg);
    const poster = item.poster ? '/' + item.poster : defaultImg;
    document.getElementById('modalBackdrop').src = backdrop;
    document.getElementById('modalPoster').src = poster;
    
    // Badge
    const badge = document.getElementById('modalBadge');
    badge.textContent = isMovie ? 'Película' : 'Serie';
    badge.className = 'inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase mb-3 ' + (isMovie ? 'bg-red-600' : 'bg-purple-600');
    
    // Título y descripción
    document.getElementById('modalTitle').textContent = item.title;
    document.getElementById('modalDescription').textContent = item.description || 'Sin descripción disponible.';
    
    // Meta info
    let meta = '';
    if (item.year || item.year_start) {
        meta += '<span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + (item.year || item.year_start) + '</span>';
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
    
    // URLs - Películas al reproductor, series a la página de detalles
    const playUrl = isMovie ? '/watch/movie/' + item.id : '/series/' + item.id;
    document.getElementById('modalPlayBtn2').href = playUrl;
    
    // Sección de episodios con selector de temporadas
    const episodesDiv = document.getElementById('modalEpisodes');
    const episodesList = document.getElementById('modalEpisodesList');
    const seasonSelector = document.getElementById('seasonSelector');
    
    if (!isMovie) {
        // Cargar episodios via API para series
        episodesDiv.classList.remove('hidden');
        seasonSelector.innerHTML = '';
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
                    seasonNumbers.forEach((sNum, idx) => {
                        const option = document.createElement('option');
                        option.value = sNum;
                        option.textContent = 'Temporada ' + sNum;
                        if (idx === 0) option.selected = true;
                        seasonSelector.appendChild(option);
                    });
                    
                    // Mostrar episodios de la primera temporada
                    if (seasonNumbers.length > 0) {
                        renderEpisodes(seasons[seasonNumbers[0]], item.id);
                    }
                } else {
                    seasonSelector.style.display = 'none';
                    episodesList.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">No hay episodios disponibles</div>';
                }
            })
            .catch(e => {
                episodesList.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Error al cargar episodios</div>';
            });
    } else {
        episodesDiv.classList.add('hidden');
        episodesList.innerHTML = '';
        seasonSelector.innerHTML = '';
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Función para cambiar temporada desde dropdown
function selectSeasonDropdown(season) {
    if (window.currentSeasons && window.currentSeriesId) {
        renderEpisodes(window.currentSeasons[season], window.currentSeriesId);
    }
}

// Renderizar episodios de una temporada
function renderEpisodes(episodes, seriesId) {
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
    document.getElementById('infoModal').classList.add('hidden');
    document.body.style.overflow = '';
    currentModalItem = null;
}

// Funciones del modal con verificación de login
function toggleModalList() {
    if (!isLoggedIn) {
        window.location.href = loginUrl;
        return;
    }
    if (!currentModalItem) return;
    const type = currentModalItem.video_path !== undefined ? 'movie' : 'series';
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
    if (!isLoggedIn) {
        window.location.href = loginUrl;
        return;
    }
    if (!currentModalItem) return;
    const type = currentModalItem.video_path !== undefined ? 'movie' : 'series';
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

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeInfoModal();
    if (e.key === 'ArrowRight' && !document.getElementById('infoModal').classList.contains('hidden')) return;
    if (e.key === 'ArrowRight') nextSlide();
    if (e.key === 'ArrowLeft') prevSlide();
});
</script>
<?php else: ?>
<!-- Hero sin contenido - Diseño Responsive -->
<section class="relative overflow-hidden pt-20 md:pt-0" style="min-height: calc(100vh - 64px); margin-top: 64px;">
    <!-- Fondo con gradientes -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/20 via-transparent to-purple-900/20"></div>
        <div class="absolute top-1/4 left-1/4 w-64 md:w-96 h-64 md:h-96 bg-red-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 md:w-96 h-64 md:h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Contenido centrado -->
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center max-w-3xl px-4 md:px-6">
            <!-- Logo -->
            <div class="relative w-20 h-20 md:w-28 md:h-28 mx-auto mb-6 md:mb-10">
                <div class="absolute inset-0 border-2 border-red-500/20 rounded-full animate-pulse"></div>
                <div class="absolute -inset-3 md:-inset-4 border border-red-500/10 rounded-full"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl md:rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-xl shadow-red-500/30">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Título -->
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6">
                Bienvenido a
                <span class="block mt-1 md:mt-2 bg-gradient-to-r from-red-400 via-red-500 to-rose-500 bg-clip-text text-transparent">
                    Jech Films
                </span>
            </h1>
            
            <!-- Descripción -->
            <p class="text-base md:text-xl text-gray-400 mb-6 md:mb-10 leading-relaxed max-w-lg mx-auto">
                Tu plataforma de streaming multimedia local.
                <span class="text-gray-500 block md:inline"> Organiza, descubre y disfruta tu contenido.</span>
            </p>
            
            <!-- Features mini -->
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 mb-6 md:mb-10">
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Películas</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Series</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="text-xs md:text-sm">Caja Fuerte</span>
                </div>
            </div>
            
            <!-- Botones -->
            <?php if (!isAuthenticated()): ?>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= url('register') ?>" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-base md:text-lg transition-all shadow-xl shadow-red-500/25">
                    Comenzar Ahora
                    <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="<?= url('login') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-semibold transition border border-white/10">
                    Ya tengo cuenta
                </a>
            </div>
            <?php else: ?>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= url('movies') ?>" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-base md:text-lg transition-all shadow-xl shadow-red-500/25">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    Explorar Películas
                </a>
                <a href="<?= url('series') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-semibold transition border border-white/10">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Ver Series
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contenido -->
<div class="container mx-auto px-4 md:px-6 lg:px-8 py-12 md:py-16 space-y-16 md:space-y-20">
    
    <!-- Categorías Creativas -->
    <?php if (!empty($categories)): ?>
    <section>
        <h2 class="text-2xl font-bold mb-8">Explorar por Categoría</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($categories as $category): 
                // Normalizar nombre para buscar en los arrays de iconos/colores
                $slug = str_replace('-', ' ', slugify($category['name']));
                $colors = $categoryColors[$slug] ?? ['from-gray-500 to-gray-700', 'bg-gray-500', 'text-gray-400', 'shadow-gray-500/30'];
                $icon = $categoryIcons[$slug] ?? $defaultIcon;
                $count = ($category['movies_count'] ?? 0) + ($category['series_count'] ?? 0);
            ?>
            <a href="<?= url('movies?category=' . $category['id']) ?>" 
               class="group relative overflow-hidden rounded-2xl bg-gradient-to-br <?= $colors[0] ?> p-[1px] transition-all hover:scale-[1.02]">
                <div class="relative bg-[#0f0f0f] rounded-[15px] p-5 h-full">
                    <div class="absolute inset-0 bg-gradient-to-br <?= $colors[0] ?> opacity-0 group-hover:opacity-10 rounded-[15px] transition-opacity"></div>
                    <div class="relative w-12 h-12 mb-4 rounded-xl <?= $colors[1] ?> flex items-center justify-center shadow-lg <?= $colors[3] ?> group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $icon ?></svg>
                    </div>
                    <h3 class="font-semibold mb-1 relative"><?= e($category['name']) ?></h3>
                    <p class="text-sm <?= $colors[2] ?> relative"><?= $count ?> <?= $count === 1 ? 'título' : 'títulos' ?></p>
                    <div class="absolute bottom-5 right-5 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                        <svg class="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Películas en Tendencia -->
    <?php if (!empty($trendingMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Películas en Tendencia</h2>
            <a href="<?= url('movies?sort=popular') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($trendingMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Series en Tendencia -->
    <?php if (!empty($trendingSeries)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Series en Tendencia</h2>
            <a href="<?= url('series?sort=popular') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($trendingSeries, 0, 6) as $series): ?>
                <?php $item = $series; include VIEWS_PATH . '/components/series-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Más gustadas -->
    <?php if (!empty($likedMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Más Gustadas</h2>
            <a href="<?= url('movies?sort=liked') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($likedMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Recientes -->
    <?php if (!empty($recentMovies)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Agregados Recientemente</h2>
            <a href="<?= url('movies?sort=recent') ?>" class="text-sm text-gray-400 hover:text-white transition">Ver todas →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php foreach (array_slice($recentMovies, 0, 6) as $movie): ?>
                <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
