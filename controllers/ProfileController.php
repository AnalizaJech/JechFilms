<?php
/**
 * Controlador de Perfil de Usuario
 */

require_once BASE_PATH . '/models/User.php';

class ProfileController {
    private User $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Muestra el perfil del usuario
     */
    public function index(): void {
        requireAuth();
        
        $user = $this->userModel->find(userId());
        $stats = $this->userModel->getStats(userId());
        
        require_once VIEWS_PATH . '/profile/index.php';
    }
    
    /**
     * Formulario de edición
     */
    public function edit(): void {
        requireAuth();
        
        $user = $this->userModel->find(userId());
        
        require_once VIEWS_PATH . '/profile/edit.php';
    }
    
    /**
     * Procesa la actualización del perfil
     */
    public function update(): void {
        requireAuth();
        
        if (!isPost() || !validateCsrf()) {
            redirect('profile');
        }
        
        $username = trim(post('username', ''));
        $email = trim(post('email', ''));
        $errors = [];
        
        // Validaciones
        if (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }
        
        if (!isValidEmail($email)) {
            $errors[] = 'El email no es válido.';
        }
        
        if ($this->userModel->usernameExists($username, userId())) {
            $errors[] = 'Este nombre de usuario ya está en uso.';
        }
        
        if ($this->userModel->emailExists($email, userId())) {
            $errors[] = 'Este email ya está registrado.';
        }
        
        // Procesar avatar si se subió
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            if (!isValidImage($_FILES['avatar'])) {
                $errors[] = 'El avatar debe ser una imagen válida (JPG, PNG, WebP).';
            } else {
                $avatar = uploadFile($_FILES['avatar'], 'avatars', 'user_');
            }
        }
        
        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
            redirect('profile/edit');
        }
        
        // Actualizar
        $data = ['username' => $username, 'email' => $email];
        if ($avatar) {
            $data['avatar'] = $avatar;
        }
        
        $this->userModel->updateProfile(userId(), $data);
        
        // Refrescar sesión
        $user = $this->userModel->find(userId());
        refreshUserSession($user);
        
        flash('success', 'Perfil actualizado correctamente.');
        redirect('profile');
    }
    
    /**
     * Cambiar contraseña
     */
    public function password(): void {
        requireAuth();
        
        if (!isPost() || !validateCsrf()) {
            redirect('profile/edit');
        }
        
        $current = post('current_password', '');
        $new = post('new_password', '');
        $confirm = post('confirm_password', '');
        
        // Verificar contraseña actual
        $user = $this->userModel->find(userId());
        if (!verifyPassword($current, $user['password'])) {
            flash('error', 'La contraseña actual es incorrecta.');
            redirect('profile/edit');
        }
        
        if (!isValidPassword($new)) {
            flash('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
            redirect('profile/edit');
        }
        
        if ($new !== $confirm) {
            flash('error', 'Las contraseñas no coinciden.');
            redirect('profile/edit');
        }
        
        $this->userModel->changePassword(userId(), $new);
        
        flash('success', 'Contraseña cambiada correctamente.');
        redirect('profile');
    }
}
