<?php
/**
 * Modelo de Reacciones
 * Gestiona los likes y dislikes de los usuarios
 */

require_once __DIR__ . '/Model.php';

class Reaction extends Model {
    protected string $table = 'reactions';
    
    /**
     * Obtiene la reacción de un usuario a un contenido
     */
    public function getUserReaction(int $userId, string $contentType, int $contentId): ?string {
        $sql = "SELECT reaction FROM {$this->table}
                WHERE user_id = :user_id AND content_type = :content_type AND content_id = :content_id";
        
        $result = $this->queryOne($sql, [
            'user_id' => $userId,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]);
        
        return $result ? $result['reaction'] : null;
    }
    
    /**
     * Establece o actualiza la reacción de un usuario
     */
    public function setReaction(int $userId, string $contentType, int $contentId, string $reaction): array {
        $current = $this->getUserReaction($userId, $contentType, $contentId);
        
        if ($current === $reaction) {
            $this->removeReaction($userId, $contentType, $contentId);
            return ['action' => 'removed', 'reaction' => null];
        } elseif ($current !== null) {
            $sql = "UPDATE {$this->table} SET reaction = :reaction
                    WHERE user_id = :user_id AND content_type = :content_type AND content_id = :content_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['reaction' => $reaction, 'user_id' => $userId, 'content_type' => $contentType, 'content_id' => $contentId]);
            return ['action' => 'updated', 'reaction' => $reaction];
        } else {
            $sql = "INSERT INTO {$this->table} (user_id, content_type, content_id, reaction) VALUES (:user_id, :content_type, :content_id, :reaction)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId, 'content_type' => $contentType, 'content_id' => $contentId, 'reaction' => $reaction]);
            return ['action' => 'created', 'reaction' => $reaction];
        }
    }
    
    /**
     * Elimina la reacción de un usuario
     */
    public function removeReaction(int $userId, string $contentType, int $contentId): bool {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id AND content_type = :content_type AND content_id = :content_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'content_type' => $contentType, 'content_id' => $contentId]);
    }
    
    /**
     * Cuenta las reacciones de un contenido
     */
    public function countReactions(string $contentType, int $contentId): array {
        $sql = "SELECT SUM(CASE WHEN reaction = 'like' THEN 1 ELSE 0 END) as likes,
                       SUM(CASE WHEN reaction = 'dislike' THEN 1 ELSE 0 END) as dislikes
                FROM {$this->table} WHERE content_type = :content_type AND content_id = :content_id";
        $result = $this->queryOne($sql, ['content_type' => $contentType, 'content_id' => $contentId]);
        return ['likes' => (int) ($result['likes'] ?? 0), 'dislikes' => (int) ($result['dislikes'] ?? 0)];
    }
}
