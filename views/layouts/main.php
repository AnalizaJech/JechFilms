<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Jech Films') ?> - <?= APP_NAME ?></title>
    <meta name="description" content="Tu plataforma de streaming multimedia personal">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'logo': ['Bebas Neue', 'sans-serif']
                    },
                    colors: {
                        'jf-dark': '#0a0a0a',
                        'jf-darker': '#050505',
                        'jf-card': '#161616',
                        'jf-card-hover': '#1f1f1f',
                        'jf-red': '#e50914',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(180deg, #0f0f1a 0%, #0a0a0a 30%, #0a0a0a 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .logo-text { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.08em; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: scale(1.03); box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        
        .nav-glass { backdrop-filter: blur(20px); background: rgba(10, 10, 10, 0.85); }
        
        /* Quitar outline rojo del navegador en inputs */
        input:focus, input:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }
        
        html { height: 100%; }
        body { display: flex; flex-direction: column; }
        main { flex: 1; }
        
        /* Dropdown animation */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        .dropdown-open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
    
    <link rel="stylesheet" href="<?= url('css/app.css') ?>">
</head>
<body class="text-white">

    <?php 
    $currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $currentPage = explode('/', $currentPath)[0] ?? 'home';
    if (empty($currentPage) || $currentPage === 'index.php') $currentPage = 'home';
    ?>
    
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 bg-black border-b border-white/10 transition-colors duration-300" id="navbar">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo -->
                <a href="<?= url('') ?>" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="logo-text text-[22px] tracking-wider">
                        <span class="text-white">JECH</span><span class="text-red-500">FILMS</span>
                    </span>
                </a>
                
                <!-- Navegación -->
                <div class="hidden md:flex items-center gap-1">
                    <?php 
                    $navItems = [
                        ['home', 'Inicio', ''],
                        ['movies', 'Películas', 'movies'],
                        ['series', 'Series', 'series'],
                    ];
                    if (isAuthenticated()) $navItems[] = ['list', 'Mi Lista', 'list'];
                    
                    foreach ($navItems as $item): 
                        $isActive = $currentPage === $item[0];
                    ?>
                    <a href="<?= url($item[2]) ?>" 
                       class="px-4 py-2 rounded-lg text-[13px] font-medium transition-all <?= $isActive ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white' ?>">
                        <?= $item[1] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                
                <!-- Acciones -->
                <div class="flex items-center gap-2">
                    <!-- Buscador Desktop -->
                    <div class="relative hidden md:block" id="searchBoxDesktop">
                        <div class="flex items-center bg-white/5 rounded-xl px-3 py-2 gap-2 min-w-[200px] lg:min-w-[280px] focus-within:bg-white/8 transition-all group">
                            <svg class="w-4 h-4 text-gray-500 group-focus-within:text-gray-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchInput" placeholder="Buscar películas, series..." 
                                   class="bg-transparent border-none outline-none text-sm text-white placeholder-gray-500 w-full focus:placeholder-gray-300"
                                   autocomplete="off">
                        </div>
                        
                        <!-- Dropdown Resultados (Desktop) -->
                        <div id="searchResults" class="absolute top-full right-0 mt-2 w-80 md:w-96 bg-[#1a1a1a] rounded-2xl shadow-2xl border border-white/10 overflow-hidden hidden z-50">
                            <div id="searchResultsList" class="max-h-96 overflow-y-auto"></div>
                            <a href="<?= url('search') ?>" id="searchViewAll" class="hidden flex items-center justify-center gap-2 px-4 py-3 bg-white/5 hover:bg-white/10 text-sm text-gray-400 hover:text-white transition border-t border-white/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Ver todos los resultados
                            </a>
                        </div>
                    </div>

                    <!-- Botón Hamburguesa Mobile -->
                    <button id="mobileMenuBtn" class="p-2 -mr-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <?php if (isAuthenticated()): ?>
                    <!-- Dropdown Usuario (Desktop) -->
                    <div class="relative hidden md:block" id="user-dropdown-container">
                        <button type="button" id="user-dropdown-btn" class="flex items-center gap-2 p-1 rounded-lg hover:bg-white/5 transition">
                            <img src="<?= avatarUrl(currentUser()['avatar'] ?? null) ?>" alt="Avatar" class="w-8 h-8 rounded-lg object-cover border border-white/10">
                            <svg class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="user-dropdown-arrow">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Menu -->
                        <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-[#1a1a1a] rounded-2xl shadow-2xl border border-white/10 overflow-hidden" id="user-dropdown-menu">
                            <!-- Header con info de usuario -->
                            <div class="p-4 bg-gradient-to-r from-red-600/20 to-transparent border-b border-white/5">
                                <div class="flex items-center gap-3">
                                    <img src="<?= avatarUrl(currentUser()['avatar'] ?? null) ?>" alt="Avatar" class="w-12 h-12 rounded-xl object-cover border border-white/10">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-white truncate"><?= e(currentUser()['username'] ?? 'Usuario') ?></p>
                                        <p class="text-sm text-gray-400 truncate"><?= e(currentUser()['email'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Links -->
                            <div class="p-2">
                                <a href="<?= url('profile') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <span>Mi Perfil</span>
                                </a>
                                
                                <?php if (isAdmin()): ?>
                                <a href="<?= url('admin/dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    </div>
                                    <span>Panel Admin</span>
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?= url('vault') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <span>Caja Fuerte</span>
                                </a>
                            </div>
                            
                            <!-- Logout -->
                            <div class="p-2 border-t border-white/5">
                                <a href="<?= url('logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-red-400 hover:bg-red-500/10 transition">
                                    <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </div>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="<?= url('login') ?>" class="hidden md:flex items-center gap-2 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Entrar
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu (Fullscreen Drawer) -->
        <div id="mobileMenu" class="fixed inset-0 z-[60] bg-black transform translate-x-full transition-transform duration-300 md:hidden flex flex-col">
            <!-- Header Mobile Menu -->
            <div class="flex items-center justify-between px-6 h-16 border-b border-white/10 bg-black">
                <!-- Logo Mobile -->
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="logo-text text-[22px] tracking-wider">
                        <span class="text-white">JECH</span><span class="text-red-500">FILMS</span>
                    </span>
                </div>
                
                <button id="closeMobileMenu" class="p-2 -mr-2 text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Mobile Search -->
            <div class="px-6 pt-6 pb-2">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 group-focus-within:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="mobileSearchInput" placeholder="Buscar..." class="w-full bg-[#161616] text-white pl-10 pr-4 py-3.5 rounded-xl border border-white/5 focus:border-white/20 focus:bg-[#202020] outline-none transition-all placeholder-gray-500 text-base">
                </div>
                <!-- Resultados Mobile -->
                <div id="mobileSearchContainer" class="mt-4 hidden bg-[#161616] border border-white/10 rounded-xl shadow-2xl z-50 overflow-hidden">
                     <div id="mobileSearchResultsList" class="max-h-[40vh] overflow-y-auto p-2 space-y-2"></div>
                     <a href="<?= url('search') ?>" id="mobileSearchViewAll" class="hidden flex items-center justify-center gap-2 px-4 py-3 bg-white/5 hover:bg-white/10 text-sm text-gray-400 hover:text-white transition border-t border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Ver todos los resultados
                    </a>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Nav Links -->
                <nav class="space-y-2">
                    <a href="<?= url('') ?>" class="block px-4 py-3 rounded-xl text-lg font-medium <?= $currentPage === 'home' ? 'bg-white/10 text-white' : 'text-gray-400' ?>">Inicio</a>
                    <a href="<?= url('movies') ?>" class="block px-4 py-3 rounded-xl text-lg font-medium <?= $currentPage === 'movies' ? 'bg-white/10 text-white' : 'text-gray-400' ?>">Películas</a>
                    <a href="<?= url('series') ?>" class="block px-4 py-3 rounded-xl text-lg font-medium <?= $currentPage === 'series' ? 'bg-white/10 text-white' : 'text-gray-400' ?>">Series</a>
                    <?php if (isAuthenticated()): ?>
                    <a href="<?= url('list') ?>" class="block px-4 py-3 rounded-xl text-lg font-medium <?= $currentPage === 'list' ? 'bg-white/10 text-white' : 'text-gray-400' ?>">Mi Lista</a>
                    <?php endif; ?>
                </nav>
                
                <?php if (isAuthenticated()): ?>
                <div class="pt-6 border-t border-white/5">
                    <div class="flex items-center gap-3 mb-6 px-4">
                        <img src="<?= avatarUrl(currentUser()['avatar'] ?? null) ?>" alt="" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-bold text-white"><?= e(currentUser()['username']) ?></p>
                            <p class="text-sm text-gray-500"><?= e(currentUser()['email']) ?></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?= url('profile') ?>" class="flex items-center justify-center gap-2 p-3 rounded-xl bg-white/5 text-sm font-medium text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Mi Perfil
                        </a>
                        <a href="<?= url('logout') ?>" class="flex items-center justify-center gap-2 p-3 rounded-xl bg-red-500/10 text-sm font-medium text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Salir
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="pt-6 border-t border-white/5 px-4">
                    <a href="<?= url('login') ?>" class="flex items-center justify-center w-full py-3 bg-red-600 rounded-xl text-white font-bold">Iniciar Sesión</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php $flash = getFlash(); if ($flash): ?>
    <div class="fixed top-20 right-6 z-50" id="flash-message">
        <div class="px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 <?= $flash['type'] === 'error' ? 'bg-red-600' : 'bg-green-600' ?>">
            <?php if ($flash['type'] === 'error'): ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php else: ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php endif; ?>
            <p class="text-sm font-medium"><?= $flash['message'] ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="border-t border-white/5 mt-auto bg-[#0a0a0a]">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                <div class="col-span-2">
                    <a href="<?= url('') ?>" class="inline-flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <span class="logo-text text-lg tracking-wider"><span class="text-white">JECH</span><span class="text-red-500">FILMS</span></span>
                    </a>
                    <p class="text-gray-500 text-sm max-w-xs leading-relaxed">Tu biblioteca multimedia personal.</p>
                </div>
                
                <div>
                    <h4 class="font-medium text-sm mb-4">Explorar</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="<?= url('movies') ?>" class="hover:text-white transition">Películas</a></li>
                        <li><a href="<?= url('series') ?>" class="hover:text-white transition">Series</a></li>
                        <li><a href="<?= url('search') ?>" class="hover:text-white transition">Buscar</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-medium text-sm mb-4">Cuenta</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <?php if (isAuthenticated()): ?>
                            <li><a href="<?= url('profile') ?>" class="hover:text-white transition">Mi Perfil</a></li>
                            <li><a href="<?= url('list') ?>" class="hover:text-white transition">Mi Lista</a></li>
                        <?php else: ?>
                            <li><a href="<?= url('login') ?>" class="hover:text-white transition">Iniciar Sesión</a></li>
                            <li><a href="<?= url('register') ?>" class="hover:text-white transition">Registrarse</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/5 mt-10 pt-6 text-center text-xs text-gray-600">
                &copy; <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?>
            </div>
        </div>
    </footer>
    
    <script src="<?= url('js/app.js') ?>"></script>
    <script>
        // Dropdown de usuario
        const dropdownContainer = document.getElementById('user-dropdown-container');
        const dropdownBtn = document.getElementById('user-dropdown-btn');
        const dropdownArrow = document.getElementById('user-dropdown-arrow');
        
        if (dropdownBtn) {
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownContainer.classList.toggle('dropdown-open');
                dropdownArrow.style.transform = dropdownContainer.classList.contains('dropdown-open') ? 'rotate(180deg)' : '';
            });
            
            document.addEventListener('click', function() {
                dropdownContainer.classList.remove('dropdown-open');
                dropdownArrow.style.transform = '';
            });
        }
        
        // Auto-hide flash
        const flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transform = 'translateX(100px)';
                flash.style.transition = 'all 0.3s ease';
                setTimeout(() => flash.remove(), 300);
            }, 4000);
        }
        
        // Búsqueda en tiempo real
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchResultsList = document.getElementById('searchResultsList');
        const searchViewAll = document.getElementById('searchViewAll');
        let searchTimeout = null;
        
        if (searchInput) {
            function performSearch(query, listEl, viewAllEl) {
                 if (query.length < 2) {
                    listEl.parentElement.classList.add('hidden');
                    return;
                }
                
                fetch('/api/search?q=' + encodeURIComponent(query))
                    .then(r => r.json())
                    .then(data => {
                        let resultHTML = '';
                        if (data.results && data.results.length > 0) {
                             data.results.forEach(item => {
                                const isMovie = item.type === 'movie';
                                const url = isMovie ? '/watch/movie/' + item.id : '/series/' + item.id;
                                const badge = isMovie ? 
                                    '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-red-600">Película</span>' : 
                                    '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-600">Serie</span>';
                                const poster = item.poster ? '/' + item.poster : '/assets/images/default-poster.svg';
                                
                                resultHTML += '<a href="' + url + '" class="flex items-center gap-3 p-3 hover:bg-white/5 transition group">';
                                resultHTML += '<img src="' + poster + '" alt="" class="w-10 h-14 object-cover rounded-lg bg-zinc-800">';
                                resultHTML += '<div class="flex-1 min-w-0">';
                                resultHTML += '<div class="flex items-center gap-2 mb-0.5">' + badge + '</div>';
                                resultHTML += '<div class="font-medium text-sm truncate group-hover:text-white transition">' + item.title + '</div>';
                                resultHTML += '</div></a>';
                            });
                             listEl.innerHTML = resultHTML;
                             listEl.innerHTML = resultHTML;
                             listEl.parentElement.classList.remove('hidden');
                             if(viewAllEl) {
                                 viewAllEl.classList.remove('hidden');
                                 // Add query param to href
                                 const currentUrl = new URL(viewAllEl.href);
                                 currentUrl.searchParams.set('q', query);
                                 viewAllEl.href = currentUrl.toString();
                             }
                        } else {
                            listEl.innerHTML = '<div class="p-4 text-center text-gray-500">Sin resultados</div>';
                            listEl.parentElement.classList.remove('hidden');
                            if(viewAllEl) viewAllEl.classList.add('hidden');
                        }
                    });
            }

            // Desktop Search
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(query, searchResultsList, searchViewAll);
                }, 300);
            });
            
            // Mobile Search
            const mobileInput = document.getElementById('mobileSearchInput');
            const mobileResultsList = document.getElementById('mobileSearchResultsList');
            const mobileViewAll = document.getElementById('mobileSearchViewAll');
            const mobileContainer = document.getElementById('mobileSearchContainer');

            if(mobileInput) {
                mobileInput.addEventListener('input', function() {
                     const query = this.value.trim();
                     if(query.length < 2) { 
                         if(mobileContainer) mobileContainer.classList.add('hidden'); 
                         return; 
                     }
                     clearTimeout(searchTimeout);
                     searchTimeout = setTimeout(() => {
                        performSearch(query, mobileResultsList, mobileViewAll);
                     }, 300);
                });
            }
        }
        
        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-x-full');
                document.body.style.overflow = 'hidden';
            });
            
            closeMobileMenu.addEventListener('click', () => {
                mobileMenu.classList.add('translate-x-full');
                document.body.style.overflow = '';
            });
        }
    </script>
</body>
</html>
