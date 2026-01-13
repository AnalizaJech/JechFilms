<?php
/**
 * Vista: Editar Usuario (Admin) - Premium
 */
$pageTitle = 'Editar Usuario';

ob_start();
?>

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-8">Editar Usuario</h1>
    
    <form action="<?= url('admin/users/update/' . $user['id']) ?>" method="POST" class="bg-[#161616] rounded-2xl border border-white/5 p-8">
        <?= csrfField() ?>
        
        <!-- Header con avatar -->
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-white/5">
            <img src="<?= avatarUrl($user['avatar']) ?>" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-white/10">
            <div>
                <p class="font-semibold text-lg"><?= e($user['username']) ?></p>
                <p class="text-sm text-gray-500">Registrado el <?= formatDate($user['created_at']) ?></p>
            </div>
        </div>
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nombre de Usuario</label>
                <input type="text" name="username" value="<?= e($user['username']) ?>" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition">
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Email</label>
                <input type="email" name="email" value="<?= e($user['email']) ?>" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition">
            </div>
            
            <!-- Dropdown de Rol personalizado -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">Rol</label>
                <div class="relative" id="role-dropdown">
                    <button type="button" class="w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-left transition hover:border-white/20" onclick="toggleRoleDropdown()">
                        <span id="role-label" class="flex items-center gap-2">
                            <?php if ($user['role'] === 'admin'): ?>
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            Administrador
                            <?php else: ?>
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Usuario
                            <?php endif; ?>
                        </span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="role-arrow">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <input type="hidden" name="role" id="role-input" value="<?= $user['role'] ?>">
                    <div class="hidden absolute top-full left-0 right-0 mt-2 bg-[#1c1c1c] rounded-xl shadow-2xl border border-white/10 overflow-hidden z-50" id="role-menu">
                        <div class="p-2">
                            <button type="button" onclick="setRole('user', 'Usuario', 'bg-blue-500')" 
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $user['role'] === 'user' ? 'bg-blue-500/15 text-blue-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Usuario
                            </button>
                            <button type="button" onclick="setRole('admin', 'Administrador', 'bg-red-500')" 
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $user['role'] === 'admin' ? 'bg-red-500/15 text-red-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Administrador
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nueva Contraseña <span class="text-gray-600">(dejar vacío para no cambiar)</span></label>
                <input type="password" name="new_password" minlength="6"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition">
            </div>
        </div>
        
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/5">
            <button type="submit" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                Guardar Cambios
            </button>
            <a href="<?= url('admin/users') ?>" class="px-6 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition">Cancelar</a>
        </div>
    </form>
</div>

<script>
let roleDropdownOpen = false;

function toggleRoleDropdown() {
    const menu = document.getElementById('role-menu');
    const arrow = document.getElementById('role-arrow');
    roleDropdownOpen = !roleDropdownOpen;
    
    if (roleDropdownOpen) {
        menu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        menu.classList.add('hidden');
        arrow.style.transform = '';
    }
}

function setRole(value, label, dotColor) {
    document.getElementById('role-input').value = value;
    document.getElementById('role-label').innerHTML = `<span class="w-2 h-2 rounded-full ${dotColor}"></span>${label}`;
    toggleRoleDropdown();
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('#role-dropdown')) {
        document.getElementById('role-menu').classList.add('hidden');
        document.getElementById('role-arrow').style.transform = '';
        roleDropdownOpen = false;
    }
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
