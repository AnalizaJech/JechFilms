<?php
/**
 * Controlador de Usuarios (Admin)
 */

require_once BASE_PATH . '/models/User.php';

class UsersController {
    private User $userModel;
    
    public function __construct() {
        requireAdmin();
        $this->userModel = new User();
    }
    
    public function index(): void {
        $users = $this->userModel->all('created_at', 'DESC');
        require_once VIEWS_PATH . '/admin/users/index.php';
    }
    
    public function edit(int $id = 0): void {
        $user = $this->userModel->find($id);
        if (!$user) {
            flash('error', 'Usuario no encontrado.');
            redirect('admin/users');
        }
        require_once VIEWS_PATH . '/admin/users/edit.php';
    }
    
    public function update(int $id = 0): void {
        if (!isPost() || !validateCsrf()) redirect('admin/users');
        
        $data = [
            'username' => trim(post('username', '')),
            'email' => trim(post('email', '')),
            'role' => post('role', 'user')
        ];
        
        // Validar que no quite el rol admin al único admin
        if ($data['role'] !== 'admin' && $id === userId()) {
            flash('error', 'No puedes quitarte el rol de administrador.');
            redirect('admin/users/edit/' . $id);
        }
        
        $this->userModel->update($id, $data);
        
        // Cambiar contraseña si se proporciona
        $newPassword = post('new_password', '');
        if (!empty($newPassword)) {
            $this->userModel->changePassword($id, $newPassword);
        }
        
        flash('success', 'Usuario actualizado.');
        redirect('admin/users');
    }
    
    public function delete(int $id = 0): void {
        if ($id === userId()) {
            flash('error', 'No puedes eliminar tu propia cuenta.');
            redirect('admin/users');
        }
        
        $this->userModel->delete($id);
        flash('success', 'Usuario eliminado.');
        redirect('admin/users');
    }
}
