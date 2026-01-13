<?php
/**
 * Modelo de Visualizaciones
 * Registra y gestiona las vistas del contenido
 */

require_once __DIR__ . '/Model.php';

class View extends Model {
    protected string $table = 'views';
    
    /**
     * Registra una nueva visualización
     */
    public function record(string $contentType, int $contentId, ?int $userId = null): int {
        return $this->create([
            'user_id' => $userId,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'watch_duration' => 0
        ]);
    }
    
    /**
     * Actualiza la duración de visualización
     */
    public function updateDuration(int $viewId, int $seconds): bool {
        return $this->update($viewId, ['watch_duration' => $seconds]);
    }
    
    /**
     * Cuenta vistas de un contenido
     */
    public function countViews(string $contentType, int $contentId): int {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE content_type = :type AND content_id = :id";
        $result = $this->queryOne($sql, ['type' => $contentType, 'id' => $contentId]);
        return (int) $result['count'];
    }
    
    /**
     * Obtiene vistas semanales por tipo de contenido
     */
    public function getWeeklyViews(string $contentType, int $limit = 10): array {
        $sql = "SELECT content_id, COUNT(*) as view_count FROM {$this->table}
                WHERE content_type = :type AND watched_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY content_id ORDER BY view_count DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':type', $contentType);
        $stmt->bindValue(':days', RANKING_WEEK_DAYS, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Estadísticas generales del sistema
     */
    public function getSystemStats(): array {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM {$this->table}) as total_views,
                    (SELECT COUNT(*) FROM {$this->table} WHERE watched_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as weekly_views,
                    (SELECT COUNT(*) FROM {$this->table} WHERE watched_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)) as daily_views";
        return $this->queryOne($sql, []);
    }
}
