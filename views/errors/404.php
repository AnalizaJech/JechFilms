<?php
/**
 * Vista: Error 404
 */
$pageTitle = 'Página no encontrada';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a0a] text-white min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <h1 class="text-8xl font-bold text-[#e50914] mb-4">404</h1>
        <h2 class="text-2xl font-semibold mb-4">Página no encontrada</h2>
        <p class="text-gray-400 mb-8">La página que buscas no existe o ha sido movida.</p>
        <a href="<?= url('') ?>" class="inline-block bg-[#e50914] hover:bg-red-700 px-6 py-3 rounded font-medium transition">
            Volver al Inicio
        </a>
    </div>
</body>
</html>
