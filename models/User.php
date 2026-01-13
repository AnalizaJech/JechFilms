<?php
/**
 * Modelo de Usuario
 * Gestiona las operaciones de la tabla users
 */

require_once __DIR__ . '/Model.php';

class User extends Model {
    protected string $table = 'users';
    
    /**
     * Busca un usuario por email
     */
    public function findByEmail(string $email): ?array {
        return $this->findOneBy('email', $email);
    }
    
    /**
     * Busca un usuario por username
     */
    public function findByUsername(string $username): ?array {
        return $this->findOneBy('username', $username);
    }
    
    /**
     * Registra un nuevo usuario
     */
    public function register(string $username, string $email, string $password): int {
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => hashPassword($password),
            'role' => 'user'
        ]);
    }
    
    /**
     * Actualiza el perfil del usuario
     */
    public function updateProfile(int $id, array $data): bool {
        $allowedFields = ['username', 'email', 'avatar'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        if (empty($updateData)) {
            return false;
        }
        
        return $this->update($id, $updateData);
    }
    
    /**
     * Cambia la contraseña del usuario
     */
    public function changePassword(int $id, string $newPassword): bool {
        return $this->update($id, [
            'password' => hashPassword($newPassword)
        ]);
    }
    
    /**
     * Obtiene usuarios con rol específico
     */
    public function getByRole(string $role): array {
        return $this->findBy('role', $role);
    }
    
    /**
     * Verifica si un email ya está registrado
     */
    public function emailExists(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch()['count'] > 0;
    }
    
    /**
     * Verifica si un username ya está registrado
     */
    public function usernameExists(string $username, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch()['count'] > 0;
    }
    
    /**
     * Obtiene estadísticas del usuario
     */
    public function getStats(int $userId): array {
        $sql = "
            SELECT 
                (SELECT COUNT(*) FROM user_lists WHERE user_id = :id1) as list_count,
                (SELECT COUNT(*) FROM reactions WHERE user_id = :id2 AND reaction = 'like') as likes_count,
                (SELECT COUNT(*) FROM views WHERE user_id = :id3) as views_count
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id1' => $userId,
            'id2' => $userId,
            'id3' => $userId
        ]);
        
        return $stmt->fetch();
    }
}
