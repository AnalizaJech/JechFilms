<?php
/**
 * Vista: Lista de Series (Admin)
 */
$pageTitle = 'Series';

ob_start();
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold">Series</h1>
    <a href="<?= url('admin/series/create') ?>" class="inline-flex items-center gap-2 bg-jf-red hover:bg-red-700 px-4 py-2 rounded-lg transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Serie
    </a>
</div>

<div class="bg-jf-card rounded-xl overflow-hidden">
    <?php if (empty($series)): ?>
        <div class="p-8 text-center text-gray-400">No hay series registradas.</div>
    <?php else: ?>
        <table class="w-full">
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
                    <td class="p-4 text-right">
                        <a href="<?= url('admin/series/edit/' . $s['id']) ?>" class="text-gray-400 hover:text-white mr-3">Editar</a>
                        <a href="<?= url('admin/series/delete/' . $s['id']) ?>" onclick="return confirm('¿Eliminar esta serie y todos sus episodios?')" class="text-red-500 hover:text-red-400">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
