<?php
/**
 * Vista: Gestión de Categorías (Admin) - Premium
 */
$pageTitle = 'Categorías';

ob_start();
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">Categorías</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Lista de categorías -->
    <div class="lg:col-span-2 bg-[#161616] rounded-2xl border border-white/5 overflow-hidden">
        <?php if (empty($categories)): ?>
            <div class="p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                No hay categorías creadas.
            </div>
        <?php else: ?>
            <table class="w-full">
                <thead class="bg-white/5 text-xs uppercase tracking-wider text-gray-400">
                    <tr>
                        <th class="text-left px-5 py-4 font-medium">Categoría</th>
                        <th class="text-center px-4 py-4 font-medium">Películas</th>
                        <th class="text-center px-4 py-4 font-medium">Series</th>
                        <th class="text-center px-4 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($categories as $cat): ?>
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="px-5 py-4">
                            <form action="<?= url('admin/categories/update/' . $cat['id']) ?>" method="POST" class="flex items-center gap-2" id="form-<?= $cat['id'] ?>">
                                <?= csrfField() ?>
                                <input type="text" name="name" value="<?= e($cat['name']) ?>" 
                                       class="bg-transparent border border-transparent hover:border-white/10 focus:border-red-500 rounded-lg px-3 py-2 text-sm w-40 transition focus:outline-none focus:bg-white/5">
                            </form>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 text-sm text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                                <?= $cat['movies_count'] ?? 0 ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 text-sm text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <?= $cat['series_count'] ?? 0 ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Guardar (icono de disco/save) -->
                                <button type="submit" form="form-<?= $cat['id'] ?>" 
                                        class="p-2 rounded-lg text-green-400 hover:bg-green-500/10 transition" title="Guardar cambios">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                </button>
                                <!-- Eliminar -->
                                <button type="button" 
                                        onclick="openDeleteModal(<?= $cat['id'] ?>, '<?= e($cat['name']) ?>')"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-500/10 transition" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Formulario nueva categoría -->
    <div class="bg-[#161616] rounded-2xl border border-white/5 p-6 h-fit">
        <div class="flex items-center gap-3 mb-6">
            <!-- Icono de popcorn -->
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h.5a4.5 4.5 0 01-.464-1.95A4.5 4.5 0 018.5 4.5a4.45 4.45 0 013.5 1.72 4.45 4.45 0 013.5-1.72 4.5 4.5 0 014.464 4.55A4.5 4.5 0 0119.5 11H20a2 2 0 012 2v7a2 2 0 01-2 2h-3l-1-4h-2l-1 4H9l-1-4H6l-1 4zm1.5-11a2.5 2.5 0 100-5 2.5 2.5 0 000 5zm7 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold">Nueva Categoría</h2>
        </div>
        <form action="<?= url('admin/categories/store') ?>" method="POST" class="space-y-4">
            <?= csrfField() ?>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nombre</label>
                <input type="text" name="name" required placeholder="Ej: Terror, Comedia..."
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 transition">
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 py-3 rounded-xl font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Categoría
            </button>
        </form>
    </div>
</div>

<!-- Modal de confirmación eliminar -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-[#1a1a1a] rounded-2xl border border-white/10 p-6 w-full max-w-md shadow-2xl">
            <div class="text-center">
                <!-- Icono -->
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold mb-2">¿Eliminar categoría?</h3>
                <p class="text-gray-400 text-sm mb-6">
                    Estás a punto de eliminar la categoría "<span id="delete-category-name" class="text-white font-medium"></span>". Esta acción no se puede deshacer.
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
    document.getElementById('delete-category-name').textContent = name;
    document.getElementById('delete-link').href = '<?= url('admin/categories/delete/') ?>' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
