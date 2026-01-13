<?php
/**
 * Vista: Mi Perfil - Ultra Premium
 */
$pageTitle = 'Mi Perfil';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6">
        
        <h1 class="text-3xl font-bold mb-10">Mi Perfil</h1>
        
        <!-- Card Principal con diseño glassmorphism -->
        <div class="relative overflow-hidden rounded-3xl">
            <!-- Fondo con gradiente animado -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-600/20 via-purple-600/10 to-blue-600/20"></div>
            <div class="absolute inset-0 bg-[#0f0f0f]/80 backdrop-blur-xl"></div>
            
            <!-- Contenido -->
            <div class="relative p-8 md:p-10">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                    <!-- Avatar con efecto glow -->
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-br from-red-500 to-purple-600 rounded-2xl blur-sm opacity-50 group-hover:opacity-75 transition"></div>
                        <img 
                            src="<?= avatarUrl($user['avatar']) ?>" 
                            alt="Avatar"
                            class="relative w-36 h-36 rounded-2xl object-cover border-2 border-white/10"
                        >
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/30">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-3xl font-bold mb-2"><?= e($user['username']) ?></h2>
                        <p class="text-gray-400 text-lg mb-3"><?= e($user['email']) ?></p>
                        <div class="flex items-center justify-center md:justify-start gap-2 text-sm text-gray-500 mb-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Miembro desde <?= formatDate($user['created_at']) ?>
                        </div>
                        
                        <a href="<?= url('profile/edit') ?>" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 px-6 py-3 rounded-xl font-semibold transition shadow-lg shadow-red-500/25">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas Premium -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
            <!-- Mi Lista -->
            <div class="group relative overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-rose-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative bg-[#161616] border border-white/5 rounded-2xl p-6 group-hover:border-red-500/30 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">En Mi Lista</p>
                            <p class="text-5xl font-bold bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent"><?= $stats['list_count'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500/20 to-rose-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Me Gusta -->
            <div class="group relative overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-emerald-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative bg-[#161616] border border-white/5 rounded-2xl p-6 group-hover:border-green-500/30 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Me Gusta</p>
                            <p class="text-5xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent"><?= $stats['likes_count'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vistas -->
            <div class="group relative overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-cyan-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative bg-[#161616] border border-white/5 rounded-2xl p-6 group-hover:border-blue-500/30 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Vistas Totales</p>
                            <p class="text-5xl font-bold bg-gradient-to-r from-blue-400 to-cyan-500 bg-clip-text text-transparent"><?= $stats['views_count'] ?? 0 ?></p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-5">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?= url('list') ?>" class="group bg-[#161616] rounded-2xl border border-white/5 p-5 hover:border-red-500/30 transition-all flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500/20 to-rose-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Mi Lista</span>
                </a>
                
                <a href="<?= url('movies') ?>" class="group bg-[#161616] rounded-2xl border border-white/5 p-5 hover:border-orange-500/30 transition-all flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500/20 to-amber-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Películas</span>
                </a>
                
                <a href="<?= url('series') ?>" class="group bg-[#161616] rounded-2xl border border-white/5 p-5 hover:border-purple-500/30 transition-all flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-violet-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Series</span>
                </a>
                
                <a href="<?= url('logout') ?>" class="group bg-[#161616] rounded-2xl border border-white/5 p-5 hover:border-gray-500/30 transition-all flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-500/20 to-slate-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-400">Salir</span>
                </a>
            </div>
        </div>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
