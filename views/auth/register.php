<?php
/**
 * Vista: Registro de Usuario - Diseño Premium
 */
$pageTitle = 'Crear Cuenta';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
            min-height: 100vh;
        }
        .logo-text { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.05em; }
        .glass-card {
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .input-field {
            background: rgba(40, 40, 40, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15);
            background: rgba(40, 40, 40, 1);
        }
        .input-field.error { border-color: #ef4444; }
        .input-field.valid { border-color: #22c55e; }
        .btn-primary {
            background: linear-gradient(135deg, #e50914 0%, #b81d24 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #f40612 0%, #e50914 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.3);
        }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body class="text-white flex flex-col min-h-screen">
    
    <!-- Header -->
    <header class="p-6">
        <a href="<?= url('') ?>" class="inline-flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
            <span class="logo-text text-2xl"><span class="text-red-500">JECH</span><span class="text-white/90">FILMS</span></span>
        </a>
    </header>
    
    <!-- Flash Messages -->
    <?php $flash = getFlash(); if ($flash): ?>
    <div class="fixed top-6 right-6 z-50">
        <div class="px-5 py-3 rounded-xl shadow-2xl backdrop-blur-lg flex items-center gap-3 <?= $flash['type'] === 'error' ? 'bg-red-600/95' : 'bg-green-600/95' ?>">
            <?php if ($flash['type'] === 'error'): ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php else: ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php endif; ?>
            <p class="text-sm font-medium"><?= $flash['message'] ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            
            <div class="glass-card rounded-3xl p-10 shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2">Crear Cuenta</h1>
                    <p class="text-gray-400">Únete a Jech Films hoy</p>
                </div>
                
                <!-- Form -->
                <form action="<?= url('register') ?>" method="POST" class="space-y-4" id="register-form">
                    <?= csrfField() ?>
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre de Usuario *
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Ej: jorge_films"
                            autocomplete="username"
                            minlength="3"
                            maxlength="50"
                            required
                            class="input-field w-full px-4 py-4 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                        >
                        <p class="text-xs text-gray-500 mt-1" id="username-hint">Mínimo 3 caracteres</p>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Correo Electrónico *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="tu@email.com"
                            autocomplete="email"
                            required
                            class="input-field w-full px-4 py-4 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                        >
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Contraseña *
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            autocomplete="new-password"
                            minlength="6"
                            required
                            class="input-field w-full px-4 py-4 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                        >
                        <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-300 mb-2">
                            Confirmar Contraseña *
                        </label>
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            placeholder="••••••••"
                            autocomplete="new-password"
                            required
                            class="input-field w-full px-4 py-4 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                        >
                        <p class="text-xs text-red-400 mt-1 hidden" id="password-match-error">Las contraseñas no coinciden</p>
                    </div>
                    
                    <button type="submit" id="submit-btn" class="btn-primary w-full py-4 rounded-xl font-semibold text-white text-lg mt-4">
                        Crear Mi Cuenta
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="flex items-center my-8">
                    <div class="flex-1 border-t border-white/10"></div>
                    <span class="px-4 text-sm text-gray-500">¿Ya tienes cuenta?</span>
                    <div class="flex-1 border-t border-white/10"></div>
                </div>
                
                <!-- Login link -->
                <a href="<?= url('login') ?>" class="block w-full text-center py-4 rounded-xl border border-white/10 text-gray-300 font-medium hover:bg-white/5 hover:border-white/20 transition">
                    Iniciar Sesión
                </a>
            </div>
            
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="p-6 text-center text-gray-600 text-sm">
        &copy; <?= date('Y') ?> <?= APP_NAME ?>
    </footer>
    
    <script>
        const form = document.getElementById('register-form');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const submitBtn = document.getElementById('submit-btn');
        const passwordMatchError = document.getElementById('password-match-error');
        
        // Validación en tiempo real
        function validateField(field, isValid) {
            field.classList.remove('error', 'valid');
            field.classList.add(isValid ? 'valid' : 'error');
        }
        
        username.addEventListener('input', function() {
            const isValid = this.value.length >= 3;
            validateField(this, isValid);
        });
        
        email.addEventListener('input', function() {
            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
            validateField(this, isValid);
        });
        
        password.addEventListener('input', function() {
            const isValid = this.value.length >= 6;
            validateField(this, isValid);
            checkPasswordMatch();
        });
        
        passwordConfirm.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            if (passwordConfirm.value.length > 0) {
                const match = password.value === passwordConfirm.value;
                validateField(passwordConfirm, match);
                passwordMatchError.classList.toggle('hidden', match);
            }
        }
        
        // Validación al enviar
        form.addEventListener('submit', function(e) {
            let valid = true;
            let errors = [];
            
            if (username.value.length < 3) {
                valid = false;
                errors.push('El nombre de usuario debe tener al menos 3 caracteres');
            }
            
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                valid = false;
                errors.push('Por favor ingresa un email válido');
            }
            
            if (password.value.length < 6) {
                valid = false;
                errors.push('La contraseña debe tener al menos 6 caracteres');
            }
            
            if (password.value !== passwordConfirm.value) {
                valid = false;
                errors.push('Las contraseñas no coinciden');
            }
            
            if (!valid) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    </script>
</body>
</html>
