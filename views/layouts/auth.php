<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Autenticación') ?> - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        'jf-dark': '#0a0a0a',
                        'jf-card': '#141414',
                        'jf-red': '#e50914',
                        'jf-red-hover': '#f40612',
                    }
                }
            }
        }
    </script>
    
    <style>
        .auth-bg {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)),
                        url('<?= url('assets/images/auth-bg.jpg') ?>');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="auth-bg min-h-screen font-sans flex flex-col">
    
    <!-- Header simple -->
    <header class="p-6">
        <a href="<?= url('') ?>" class="inline-flex items-center gap-2">
            <span class="text-jf-red text-3xl font-bold tracking-tight">JECH</span>
            <span class="text-white text-xl font-light">Films</span>
        </a>
    </header>
    
    <!-- Contenido -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="fixed top-4 right-4 z-50 max-w-sm">
            <div class="<?= $flash['type'] === 'error' ? 'bg-red-500/90' : 'bg-green-500/90' ?> text-white px-6 py-4 rounded-lg shadow-lg">
                <?= $flash['message'] ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer simple -->
    <footer class="p-6 text-center text-sm text-gray-500">
        © <?= date('Y') ?> Jech Films
    </footer>
</body>
</html>
