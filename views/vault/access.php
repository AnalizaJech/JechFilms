<?php
/**
 * Vista: Acceso a Caja Fuerte
 */
$pageTitle = 'Acceso Restringido';

ob_start();
?>

<div class="min-h-screen flex items-center justify-center pt-20 pb-12">
    <div class="w-full max-w-md px-4">
        <div class="bg-jf-card/90 backdrop-blur rounded-xl p-8 text-center">
            
            <!-- Icono de candado -->
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold mb-2">Contenido Restringido</h1>
            <p class="text-gray-400 mb-8">Introduce el código de acceso para continuar</p>
            
            <form action="<?= url('vault/verify') ?>" method="POST">
                <?= csrfField() ?>
                
                <div class="mb-6">
                    <input 
                        type="password" 
                        name="code" 
                        placeholder="••••"
                        autocomplete="off"
                        maxlength="10"
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-4 text-center text-2xl tracking-widest focus:outline-none focus:border-jf-red"
                        required
                    >
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 py-3 rounded-lg font-semibold transition">
                    Acceder
                </button>
            </form>
            
            <a href="<?= url('') ?>" class="inline-block mt-6 text-sm text-gray-400 hover:text-white">
                ← Volver al inicio
            </a>
            
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
