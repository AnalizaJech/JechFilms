<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        'jf-card': '#161616',
                        'jf-red': '#e50914',
                    }
                }
            }
        }
    </script>
    
    <style>
        .logo-text { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.08em; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white font-sans min-h-screen">
    
    <?php 
    $currentPath = $_SERVER['REQUEST_URI'];
    $navItems = [
        ['dashboard', 'Dashboard', 'admin/dashboard', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>'],
        ['users', 'Usuarios', 'admin/users', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
        ['movies', 'Películas', 'admin/movies', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>'],
        ['series', 'Series', 'admin/series', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>'],
        ['categories', 'Categorías', 'admin/categories', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>'],
        ['vault', 'Caja Fuerte', 'admin/vault', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>'],
    ];
    ?>
    
    <div class="flex min-h-screen">
        <!-- Mobile Header - Altura fija para evitar estiramientos -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-[#0a0a0a] border-b border-white/10 px-4 h-16 flex items-center justify-between">
            <a href="<?= url('') ?>" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                <span class="logo-text text-lg">
                    <span class="text-white">JECH</span><span class="text-red-500">FILMS</span>
                </span>
            </a>
            <button onclick="toggleSidebar()" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/5 fixed h-full z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <!-- Logo (desktop) -->
            <div class="p-6 border-b border-white/5 hidden lg:block">
                <a href="<?= url('') ?>" class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="logo-text text-xl tracking-wider">
                        <span class="text-white">JECH</span><span class="text-red-500">FILMS</span>
                    </span>
                </a>
                <span class="text-xs text-gray-500">Panel de Administración</span>
            </div>
            
            <!-- Mobile close button -->
            <div class="lg:hidden p-4 border-b border-white/5 flex items-center justify-between">
                <span class="text-sm text-gray-400">Menú</span>
                <button onclick="toggleSidebar()" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Navegación -->
            <nav class="p-3 space-y-1 mt-2 lg:mt-0">
                <?php foreach ($navItems as $item): 
                    $isActive = strpos($currentPath, $item[0]) !== false;
                ?>
                <a href="<?= url($item[2]) ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition <?= $isActive ? 'bg-red-500/10 text-white border-l-2 border-red-500' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                    <svg class="w-5 h-5 <?= $isActive ? 'text-red-400' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $item[3] ?></svg>
                    <?= $item[1] ?>
                </a>
                <?php endforeach; ?>
            </nav>
            
            <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-white/5">
                <a href="<?= url('') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-500 hover:bg-white/5 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al sitio
                </a>
            </div>
        </aside>
        
        <!-- Main content -->
        <main class="flex-1 lg:ml-64 p-4 lg:p-8 bg-[#0f0f0f] pt-16 lg:pt-8 overflow-x-hidden">
            <!-- Flash messages -->
            <?php $flash = getFlash(); if ($flash): ?>
            <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3 <?= $flash['type'] === 'error' ? 'bg-red-500/10 border border-red-500/30 text-red-400' : 'bg-green-500/10 border border-green-500/30 text-green-400' ?>">
                <?php if ($flash['type'] === 'error'): ?>
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php else: ?>
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php endif; ?>
                <span class="text-sm"><?= $flash['message'] ?></span>
            </div>
            <?php endif; ?>
            
            <?= $content ?? '' ?>
        </main>
    </div>
    
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    </script>
    
</body>
</html>
