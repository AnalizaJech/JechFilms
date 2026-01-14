<?php
/**
 * Vista: Lista de Series (Admin) - Con iconos y modal
 */
$pageTitle = 'Series';

ob_start();
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold">Series</h1>
    <a href="<?= url('admin/series/create') ?>" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Serie
    </a>
</div>

<div class="bg-jf-card rounded-xl overflow-hidden">
    <?php if (empty($series)): ?>
        <div class="p-8 text-center text-gray-400">No hay series registradas.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-white/5">
                <tr>
                    <th class="text-left p-4 font-medium">Serie</th>
                    <th class="text-left p-4 font-medium">Años</th>
                    <th class="text-center p-4 font-medium">Temporadas</th>
                    <th class="text-center p-4 font-medium">Episodios</th>
                    <th class="text-center p-4 font-medium">Estado</th>
                    <th class="text-right p-4 font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($series as $s): ?>
                <tr class="hover:bg-white/5">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="<?= posterUrl($s['poster']) ?>" alt="" class="w-10 h-14 object-cover rounded">
                            <span class="font-medium"><?= e($s['title']) ?></span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-400"><?= e($s['year_start'] ?? '') ?><?= $s['year_end'] ? '-' . $s['year_end'] : '' ?></td>
                    <td class="p-4 text-center"><?= $s['total_seasons'] ?? 0 ?></td>
                    <td class="p-4 text-center"><?= $s['total_episodes'] ?? 0 ?></td>
                    <td class="p-4 text-center">
                        <?php if ($s['is_featured']): ?><span class="inline-block px-2 py-1 bg-yellow-500/20 text-yellow-500 rounded text-xs mr-1">Destacado</span><?php endif; ?>
                        <?php if ($s['is_vault']): ?><span class="inline-block px-2 py-1 bg-pink-500/20 text-pink-500 rounded text-xs">Caja Fuerte</span><?php endif; ?>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= url('admin/series/edit/' . $s['id']) ?>" 
                               class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="openDeleteModal(<?= $s['id'] ?>, '<?= e(addslashes($s['title'])) ?>')" 
                                    class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmación para eliminar -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative bg-[#1a1a1a] rounded-2xl p-6 max-w-md w-full border border-white/10">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">¿Eliminar serie?</h3>
                <p class="text-gray-400 mb-6">Estás a punto de eliminar "<span id="seriesTitle" class="text-white font-medium"></span>" y todos sus episodios. Esta acción no se puede deshacer.</p>
                <div class="flex items-center justify-center gap-3">
                    <button onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition font-medium">
                        Cancelar
                    </button>
                    <a id="deleteLink" href="#" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 transition font-medium">
                        Sí, eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, title) {
    document.getElementById('seriesTitle').textContent = title;
    document.getElementById('deleteLink').href = '<?= url('admin/series/delete/') ?>' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
