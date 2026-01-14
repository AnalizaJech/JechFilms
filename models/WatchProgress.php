<?php
/**
 * Modelo: WatchProgress
 * Gestiona el progreso de reproducción de usuarios
 * Permite implementar "Continuar viendo"
 */

class WatchProgress {
    private PDO $db;
    
    public function __construct() {
        $this->db = getConnection();
    }
    
    /**
     * Obtiene el progreso de un contenido para un usuario
     */
    public function getProgress(int $userId, string $contentType, int $contentId): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM watch_progress 
            WHERE user_id = ? AND content_type = ? AND content_id = ?
        ");
        $stmt->execute([$userId, $contentType, $contentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Guarda o actualiza el progreso de reproducción
     */
    public function saveProgress(int $userId, string $contentType, int $contentId, int $progressSeconds, int $durationSeconds): bool {
        // Determinar si está completado (vio más del 90%)
        $isCompleted = $durationSeconds > 0 && ($progressSeconds / $durationSeconds) >= 0.9;
        
        $stmt = $this->db->prepare("
            INSERT INTO watch_progress (user_id, content_type, content_id, progress_seconds, duration_seconds, is_completed)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                progress_seconds = VALUES(progress_seconds),
                duration_seconds = VALUES(duration_seconds),
                is_completed = VALUES(is_completed),
                updated_at = CURRENT_TIMESTAMP
        ");
        
        return $stmt->execute([$userId, $contentType, $contentId, $progressSeconds, $durationSeconds, $isCompleted]);
    }
    
    /**
     * Marca contenido como completado
     */
    public function markCompleted(int $userId, string $contentType, int $contentId): bool {
        $stmt = $this->db->prepare("
            UPDATE watch_progress 
            SET is_completed = TRUE, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ? AND content_type = ? AND content_id = ?
        ");
        return $stmt->execute([$userId, $contentType, $contentId]);
    }
    
    /**
     * Reinicia el progreso (empezar de nuevo)
     */
    public function resetProgress(int $userId, string $contentType, int $contentId): bool {
        $stmt = $this->db->prepare("
            UPDATE watch_progress 
            SET progress_seconds = 0, is_completed = FALSE, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ? AND content_type = ? AND content_id = ?
        ");
        return $stmt->execute([$userId, $contentType, $contentId]);
    }
    
    /**
     * Obtiene contenido que el usuario está viendo (no completado)
     */
    public function getContinueWatching(int $userId, int $limit = 10): array {
        $stmt = $this->db->prepare("
            SELECT wp.*, 
                CASE wp.content_type 
                    WHEN 'movie' THEN m.title
                    WHEN 'episode' THEN CONCAT(s.title, ' - ', e.title)
                END as title,
                CASE wp.content_type 
                    WHEN 'movie' THEN m.poster
                    WHEN 'episode' THEN COALESCE(e.thumbnail, s.poster)
                END as poster,
                CASE wp.content_type 
                    WHEN 'movie' THEN NULL
                    WHEN 'episode' THEN e.series_id
                END as series_id
            FROM watch_progress wp
            LEFT JOIN movies m ON wp.content_type = 'movie' AND wp.content_id = m.id
            LEFT JOIN episodes e ON wp.content_type = 'episode' AND wp.content_id = e.id
            LEFT JOIN series s ON e.series_id = s.id
            WHERE wp.user_id = ? 
                AND wp.is_completed = FALSE 
                AND wp.progress_seconds > 30
            ORDER BY wp.updated_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verifica si debe mostrar "Continuar" o "Reproducir"
     * Retorna: 'continue' si hay progreso, 'play' si es nuevo o completado
     */
    public function getWatchState(int $userId, string $contentType, int $contentId): array {
        $progress = $this->getProgress($userId, $contentType, $contentId);
        
        if (!$progress) {
            return ['state' => 'play', 'progress' => 0, 'label' => 'Reproducir'];
        }
        
        if ($progress['is_completed']) {
            return ['state' => 'play', 'progress' => 0, 'label' => 'Reproducir'];
        }
        
        if ($progress['progress_seconds'] > 30) {
            $minutes = floor($progress['progress_seconds'] / 60);
            $remaining = floor(($progress['duration_seconds'] - $progress['progress_seconds']) / 60);
            return [
                'state' => 'continue', 
                'progress' => $progress['progress_seconds'],
                'label' => "Continuar ({$remaining} min restantes)"
            ];
        }
        
        return ['state' => 'play', 'progress' => 0, 'label' => 'Reproducir'];
    }
}
