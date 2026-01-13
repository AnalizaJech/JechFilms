<?php
/**
 * Vista: Lista de Usuarios (Admin) - Premium
 */
$pageTitle = 'Usuarios';

ob_start();
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">Usuarios</h1>
    <span class="text-sm text-gray-500"><?= count($users) ?> usuarios registrados</span>
</div>

<div class="bg-[#161616] rounded-2xl border border-white/5 overflow-hidden">
    <table class="w-full">
        <thead class="bg-white/5 text-xs uppercase tracking-wider text-gray-400">
            <tr>
                <th class="text-left px-5 py-4 font-medium">Usuario</th>
                <th class="text-left px-4 py-4 font-medium">Email</th>
                <th class="text-center px-4 py-4 font-medium">Rol</th>
                <th class="text-left px-4 py-4 font-medium">Registro</th>
                <th class="text-center px-4 py-4 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            <?php foreach ($users as $u): ?>
            <tr class="hover:bg-white/[0.02] transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="<?= avatarUrl($u['avatar']) ?>" alt="" class="w-10 h-10 rounded-full object-cover border border-white/10">
                        <span class="font-medium"><?= e($u['username']) ?></span>
                    </div>
                </td>
                <td class="px-4 py-4 text-gray-400 text-sm"><?= e($u['email']) ?></td>
                <td class="px-4 py-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-medium <?= $u['role'] === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' ?>">
                        <?= $u['role'] === 'admin' ? 'Admin' : 'Usuario' ?>
                    </span>
                </td>
                <td class="px-4 py-4 text-gray-500 text-sm"><?= formatDate($u['created_at']) ?></td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <!-- Editar -->
                        <a href="<?= url('admin/users/edit/' . $u['id']) ?>" 
                           class="p-2 rounded-lg text-blue-400 hover:bg-blue-500/10 transition" title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <!-- Eliminar (solo si no es el usuario actual) -->
                        <?php if ($u['id'] !== userId()): ?>
                        <button type="button" 
                                onclick="openDeleteModal(<?= $u['id'] ?>, '<?= e($u['username']) ?>')"
                                class="p-2 rounded-lg text-red-400 hover:bg-red-500/10 transition" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de confirmación eliminar -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-[#1a1a1a] rounded-2xl border border-white/10 p-6 w-full max-w-md shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold mb-2">¿Eliminar usuario?</h3>
                <p class="text-gray-400 text-sm mb-6">
                    Estás a punto de eliminar al usuario "<span id="delete-user-name" class="text-white font-medium"></span>". Esta acción eliminará todos sus datos y no se puede deshacer.
                </p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3 rounded-xl bg-white/10 hover:bg-white/15 font-medium transition">
                        Cancelar
                    </button>
                    <a id="delete-link" href="#" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 font-medium transition text-center">
                        Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, name) {
    document.getElementById('delete-user-name').textContent = name;
    document.getElementById('delete-link').href = '<?= url('admin/users/delete/') ?>' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
