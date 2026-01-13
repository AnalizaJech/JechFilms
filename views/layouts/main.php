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
    <nav class="fixed w-full top-0 z-50 nav-glass border-b border-white/5">
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
                    <a href="<?= url('search') ?>" class="p-2.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </a>
                    
                    <?php if (isAuthenticated()): ?>
                    <!-- Dropdown Usuario -->
                    <div class="relative" id="user-dropdown-container">
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
                    <a href="<?= url('login') ?>" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Entrar
                    </a>
                    <?php endif; ?>
                </div>
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
    </script>
</body>
</html>
