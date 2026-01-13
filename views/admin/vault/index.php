<?php
/**
 * Vista: Configuración de Caja Fuerte (Admin)
 */
$pageTitle = 'Caja Fuerte';

ob_start();
?>

<h1 class="text-3xl font-bold mb-8">Caja Fuerte</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Estado y configuración -->
    <div class="bg-jf-card rounded-xl p-8">
        <h2 class="text-xl font-semibold mb-6">Configuración</h2>
        
        <!-- Estado actual -->
        <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-lg mb-6">
            <div>
                <p class="font-medium">Estado de la Caja Fuerte</p>
                <p class="text-sm text-gray-400">
                    <?= $settings['is_enabled'] ? 'Los usuarios pueden acceder con el código' : 'El acceso está deshabilitado' ?>
                </p>
            </div>
            <form action="<?= url('admin/vault/toggle') ?>" method="POST">
                <?= csrfField() ?>
                <button type="submit" class="px-4 py-2 rounded-lg font-medium <?= $settings['is_enabled'] ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' ?>">
                    <?= $settings['is_enabled'] ? 'Deshabilitar' : 'Habilitar' ?>
                </button>
            </form>
        </div>
        
        <!-- Cambiar código -->
        <form action="<?= url('admin/vault/updateCode') ?>" method="POST" class="space-y-4">
            <?= csrfField() ?>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nuevo Código de Acceso</label>
                <input type="password" name="new_code" required minlength="4" placeholder="Mínimo 4 caracteres"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Confirmar Código</label>
                <input type="password" name="confirm_code" required
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 focus:outline-none focus:border-jf-red">
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 py-3 rounded-lg font-medium">
                Cambiar Código
            </button>
        </form>
    </div>
    
    <!-- Contenido en la caja fuerte -->
    <div class="bg-jf-card rounded-xl p-8">
        <h2 class="text-xl font-semibold mb-6">Contenido Actual</h2>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-jf-red/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-jf-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <span>Películas</span>
                </div>
                <span class="text-2xl font-bold"><?= count($movies) ?></span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span>Series</span>
                </div>
                <span class="text-2xl font-bold"><?= count($series) ?></span>
            </div>
        </div>
        
        <p class="text-sm text-gray-400 mt-6">
            Para agregar contenido a la caja fuerte, edita una película o serie y marca la opción "Contenido de Caja Fuerte".
        </p>
    </div>
</div>

<!-- Lista de contenido -->
<?php if (!empty($movies) || !empty($series)): ?>
<div class="mt-8 bg-jf-card rounded-xl p-8">
    <h2 class="text-xl font-semibold mb-6">Contenido Detallado</h2>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php foreach ($movies as $movie): ?>
        <div class="group relative">
            <img src="<?= posterUrl($movie['poster']) ?>" alt="" class="w-full aspect-[2/3] object-cover rounded-lg">
            <div class="absolute top-2 left-2 bg-jf-red px-2 py-0.5 rounded text-xs">Película</div>
            <p class="mt-2 text-sm truncate"><?= e($movie['title']) ?></p>
        </div>
        <?php endforeach; ?>
        
        <?php foreach ($series as $s): ?>
        <div class="group relative">
            <img src="<?= posterUrl($s['poster']) ?>" alt="" class="w-full aspect-[2/3] object-cover rounded-lg">
            <div class="absolute top-2 left-2 bg-purple-500 px-2 py-0.5 rounded text-xs">Serie</div>
            <p class="mt-2 text-sm truncate"><?= e($s['title']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/admin.php';
?>
