<?php
/**
 * Controlador de Autenticación
 * Gestiona login, registro y logout
 */

require_once BASE_PATH . '/models/User.php';

class AuthController {
    private User $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Muestra el formulario de login
     */
    public function index(): void {
        // Si ya está autenticado, redirigir a inicio
        if (isAuthenticated()) {
            redirect('');
        }
        
        require_once VIEWS_PATH . '/auth/login.php';
    }
    
    /**
     * Procesa el login
     */
    public function login(): void {
        if (!isPost()) {
            redirect('login');
        }
        
        if (!validateCsrf()) {
            flash('error', 'Token de seguridad inválido. Intenta de nuevo.');
            redirect('login');
        }
        
        // Rate limiting para prevenir fuerza bruta
        if (!checkRateLimit('login', 5, 300)) {
            flash('error', 'Demasiados intentos. Espera 5 minutos.');
            redirect('login');
        }
        
        $credential = trim(post('credential', ''));
        $password = post('password', '');
        
        // Validaciones básicas
        if (empty($credential) || empty($password)) {
            flash('error', 'Por favor completa todos los campos.');
            redirect('login');
        }
        
        // Buscar usuario por email O username
        $user = null;
        if (filter_var($credential, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userModel->findByEmail($credential);
        }
        
        if (!$user) {
            $user = $this->userModel->findByUsername($credential);
        }
        
        if (!$user || !verifyPassword($password, $user['password'])) {
            flash('error', 'Credenciales incorrectas.');
            redirect('login');
        }
        
        // Login exitoso
        resetRateLimit('login');
        loginUser($user);
        flash('success', '¡Bienvenido, ' . e($user['username']) . '!');
        
        // Redirigir según rol
        if ($user['role'] === 'admin') {
            redirect('admin/dashboard');
        }
        redirect('');
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function register(): void {
        if (isAuthenticated()) {
            redirect('');
        }
        
        require_once VIEWS_PATH . '/auth/register.php';
    }
    
    /**
     * Procesa el registro
     */
    public function store(): void {
        if (!isPost()) {
            redirect('register');
        }
        
        if (!validateCsrf()) {
            flash('error', 'Token de seguridad inválido.');
            redirect('register');
        }
        
        $username = trim(post('username', ''));
        $email = trim(post('email', ''));
        $password = post('password', '');
        $passwordConfirm = post('password_confirm', '');
        
        // Validaciones
        $errors = [];
        
        if (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }
        
        if (!isValidEmail($email)) {
            $errors[] = 'El email no es válido.';
        }
        
        if (!isValidPassword($password)) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        }
        
        if ($password !== $passwordConfirm) {
            $errors[] = 'Las contraseñas no coinciden.';
        }
        
        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Este email ya está registrado.';
        }
        
        if ($this->userModel->usernameExists($username)) {
            $errors[] = 'Este nombre de usuario ya está en uso.';
        }
        
        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
            redirect('register');
        }
        
        // Crear usuario
        $userId = $this->userModel->register($username, $email, $password);
        $user = $this->userModel->find($userId);
        
        // Auto login
        loginUser($user);
        flash('success', '¡Cuenta creada exitosamente! Bienvenido a Jech Films.');
        redirect('');
    }
    
    /**
     * Cierra la sesión
     */
    public function logout(): void {
        logoutUser();
        flash('success', 'Has cerrado sesión correctamente.');
        redirect('login');
    }
}
