<?php
/**
 * Vista: Editar Perfil - Premium
 */
$pageTitle = 'Editar Perfil';

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6 max-w-2xl">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-10">
            <a href="<?= url('profile') ?>" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold">Editar Perfil</h1>
        </div>
        
        <!-- Formulario de perfil -->
        <div class="relative overflow-hidden rounded-2xl mb-6">
            <div class="absolute inset-0 bg-gradient-to-br from-red-600/10 via-transparent to-purple-600/10"></div>
            <div class="relative bg-[#161616]/90 border border-white/5 p-8 rounded-2xl">
                <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Información Personal
                </h2>
                
                <form action="<?= url('profile/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?= csrfField() ?>
                    
                    <!-- Avatar -->
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-br from-red-500 to-purple-600 rounded-2xl blur-sm opacity-40 group-hover:opacity-60 transition"></div>
                            <img src="<?= avatarUrl($user['avatar']) ?>" alt="" class="relative w-24 h-24 rounded-2xl object-cover border border-white/10">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm text-gray-400 mb-2">Cambiar Avatar</label>
                            <input type="file" name="avatar" accept="image/*" 
                                   class="text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:bg-red-600 file:hover:bg-red-700 file:text-white file:font-medium file:cursor-pointer file:transition">
                        </div>
                    </div>
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm text-gray-400 mb-2">Nombre de Usuario</label>
                        <input type="text" id="username" name="username" value="<?= e($user['username']) ?>" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 focus:outline-none focus:border-red-500 transition">
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm text-gray-400 mb-2">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 focus:outline-none focus:border-red-500 transition">
                    </div>
                    
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 py-3.5 rounded-xl font-semibold transition shadow-lg shadow-red-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Cambiar contraseña -->
        <div class="relative overflow-hidden rounded-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-600/10 via-transparent to-slate-600/10"></div>
            <div class="relative bg-[#161616]/90 border border-white/5 p-8 rounded-2xl">
                <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Cambiar Contraseña
                </h2>
                
                <form action="<?= url('profile/password') ?>" method="POST" class="space-y-5">
                    <?= csrfField() ?>
                    
                    <div>
                        <label for="current_password" class="block text-sm text-gray-400 mb-2">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 focus:outline-none focus:border-gray-400 transition">
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm text-gray-400 mb-2">Nueva Contraseña</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 focus:outline-none focus:border-gray-400 transition">
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm text-gray-400 mb-2">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 focus:outline-none focus:border-gray-400 transition">
                    </div>
                    
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white/10 hover:bg-white/15 py-3.5 rounded-xl font-semibold transition border border-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
