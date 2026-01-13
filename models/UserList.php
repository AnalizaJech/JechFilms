<?php
/**
 * Modelo de Lista de Usuario
 * Gestiona la lista personal "Mi Lista" de cada usuario
 */

require_once __DIR__ . '/Model.php';

class UserList extends Model {
    protected string $table = 'user_lists';
    
    /**
     * Obtiene la lista completa de un usuario con detalles del contenido
     */
    public function getUserList(int $userId): array {
        $sql = "
            SELECT 
                ul.*,
                CASE 
                    WHEN ul.content_type = 'movie' THEN m.title
                    WHEN ul.content_type = 'series' THEN s.title
                END as title,
                CASE 
                    WHEN ul.content_type = 'movie' THEN m.poster
                    WHEN ul.content_type = 'series' THEN s.poster
                END as poster,
                CASE 
                    WHEN ul.content_type = 'movie' THEN m.year
                    WHEN ul.content_type = 'series' THEN s.year_start
                END as year,
                CASE 
                    WHEN ul.content_type = 'movie' THEN m.description
                    WHEN ul.content_type = 'series' THEN s.description
                END as description
            FROM user_lists ul
            LEFT JOIN movies m ON ul.content_type = 'movie' AND ul.content_id = m.id
            LEFT JOIN series s ON ul.content_type = 'series' AND ul.content_id = s.id
            WHERE ul.user_id = :user_id
            ORDER BY ul.created_at DESC
        ";
        
        return $this->query($sql, ['user_id' => $userId]);
    }
    
    /**
     * Verifica si un contenido está en la lista del usuario
     */
    public function isInList(int $userId, string $contentType, int $contentId): bool {
        $sql = "
            SELECT COUNT(*) as count FROM {$this->table}
            WHERE user_id = :user_id 
                  AND content_type = :content_type 
                  AND content_id = :content_id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]);
        
        return $stmt->fetch()['count'] > 0;
    }
    
    /**
     * Añade contenido a la lista del usuario
     */
    public function addToList(int $userId, string $contentType, int $contentId): bool {
        if ($this->isInList($userId, $contentType, $contentId)) {
            return false; // Ya está en la lista
        }
        
        $sql = "
            INSERT INTO {$this->table} (user_id, content_type, content_id)
            VALUES (:user_id, :content_type, :content_id)
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]);
    }
    
    /**
     * Elimina contenido de la lista del usuario
     */
    public function removeFromList(int $userId, string $contentType, int $contentId): bool {
        $sql = "
            DELETE FROM {$this->table}
            WHERE user_id = :user_id 
                  AND content_type = :content_type 
                  AND content_id = :content_id
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]);
    }
    
    /**
     * Alterna el estado de un contenido en la lista
     */
    public function toggle(int $userId, string $contentType, int $contentId): array {
        if ($this->isInList($userId, $contentType, $contentId)) {
            $this->removeFromList($userId, $contentType, $contentId);
            return ['action' => 'removed', 'in_list' => false];
        } else {
            $this->addToList($userId, $contentType, $contentId);
            return ['action' => 'added', 'in_list' => true];
        }
    }
    
    /**
     * Cuenta cuántos elementos tiene el usuario en su lista
     */
    public function countUserItems(int $userId): int {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        
        return (int) $stmt->fetch()['count'];
    }
}
