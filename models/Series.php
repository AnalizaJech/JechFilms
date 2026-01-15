<?php
/**
 * Modelo de Serie
 * Gestiona las operaciones de las tablas series y episodes
 */

require_once __DIR__ . '/Model.php';

class Series extends Model {
    protected string $table = 'series';
    
    /**
     * Obtiene series con sus categorías
     */
    public function getAllWithCategories(bool $includeVault = false): array {
        $vaultCondition = $includeVault ? '' : 'WHERE s.is_vault = FALSE';
        
        $sql = "
            SELECT s.*, 
                   GROUP_CONCAT(c.name SEPARATOR ', ') as categories,
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            LEFT JOIN series_categories sc ON s.id = sc.series_id
            LEFT JOIN categories c ON sc.category_id = c.id
            {$vaultCondition}
            GROUP BY s.id
            ORDER BY s.created_at DESC
        ";
        
        return $this->query($sql);
    }
    
    /**
     * Obtiene una serie con todos sus detalles
     */
    public function getWithDetails(int $id): ?array {
        $sql = "
            SELECT s.*,
                   GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories,
                   GROUP_CONCAT(DISTINCT c.id SEPARATOR ',') as category_ids,
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes,
                   (SELECT COUNT(*) FROM reactions WHERE content_type = 'series' AND content_id = s.id AND reaction = 'like') as likes_count,
                   (SELECT COUNT(*) FROM reactions WHERE content_type = 'series' AND content_id = s.id AND reaction = 'dislike') as dislikes_count
            FROM series s
            LEFT JOIN series_categories sc ON s.id = sc.series_id
            LEFT JOIN categories c ON sc.category_id = c.id
            WHERE s.id = :id
            GROUP BY s.id
        ";
        
        return $this->queryOne($sql, ['id' => $id]);
    }
    
    /**
     * Obtiene los episodios de una serie organizados por temporada
     */
    public function getEpisodes(int $seriesId): array {
        $sql = "
            SELECT * FROM episodes 
            WHERE series_id = :series_id
            ORDER BY season ASC, episode_number ASC
        ";
        
        $episodes = $this->query($sql, ['series_id' => $seriesId]);
        
        // Organizar por temporadas
        $seasons = [];
        foreach ($episodes as $episode) {
            $seasons[$episode['season']][] = $episode;
        }
        
        return $seasons;
    }
    
    /**
     * Obtiene un episodio específico
     */
    public function getEpisode(int $episodeId): ?array {
        $sql = "
            SELECT e.*, s.title as series_title, s.poster as series_poster
            FROM episodes e
            INNER JOIN series s ON e.series_id = s.id
            WHERE e.id = :id
        ";
        
        return $this->queryOne($sql, ['id' => $episodeId]);
    }
    
    /**
     * Obtiene el primer episodio de una serie
     */
    public function getFirstEpisode(int $seriesId): ?array {
        $sql = "
            SELECT * FROM episodes 
            WHERE series_id = :series_id
            ORDER BY season ASC, episode_number ASC
            LIMIT 1
        ";
        
        return $this->queryOne($sql, ['series_id' => $seriesId]);
    }
    
    /**
     * Obtiene el siguiente episodio
     */
    public function getNextEpisode(int $seriesId, int $season, int $episodeNumber): ?array {
        // Primero buscar en la misma temporada
        $sql = "
            SELECT * FROM episodes 
            WHERE series_id = :series_id 
                  AND season = :season 
                  AND episode_number > :episode
            ORDER BY episode_number ASC
            LIMIT 1
        ";
        
        $next = $this->queryOne($sql, [
            'series_id' => $seriesId,
            'season' => $season,
            'episode' => $episodeNumber
        ]);
        
        if ($next) {
            return $next;
        }
        
        // Si no hay, buscar en la siguiente temporada
        $sql = "
            SELECT * FROM episodes 
            WHERE series_id = :series_id AND season > :season
            ORDER BY season ASC, episode_number ASC
            LIMIT 1
        ";
        
        return $this->queryOne($sql, [
            'series_id' => $seriesId,
            'season' => $season
        ]);
    }
    
    /**
     * Obtiene series destacadas
     */
    public function getFeatured(int $limit = 5): array {
        $sql = "
            SELECT s.*, 
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            WHERE s.is_featured = TRUE AND s.is_vault = FALSE
            ORDER BY RAND()
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene series más vistas de la semana
     */
    public function getWeeklyTrending(int $limit = 10): array {
        $sql = "
            SELECT s.*, COUNT(v.id) as view_count,
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            INNER JOIN episodes e ON e.series_id = s.id
            INNER JOIN views v ON v.content_type = 'episode' AND v.content_id = e.id
            WHERE v.watched_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                  AND s.is_vault = FALSE
            GROUP BY s.id
            ORDER BY view_count DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', RANKING_WEEK_DAYS, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene series más gustadas
     */
    public function getMostLiked(int $limit = 10): array {
        $sql = "
            SELECT s.*, COUNT(r.id) as like_count,
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            INNER JOIN reactions r ON r.content_type = 'series' AND r.content_id = s.id AND r.reaction = 'like'
            WHERE s.is_vault = FALSE
            GROUP BY s.id
            ORDER BY like_count DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene series recientes
     */
    public function getRecent(int $limit = 10): array {
        $sql = "
            SELECT s.*, 
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            WHERE s.is_vault = FALSE
            ORDER BY s.created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Crea un nuevo episodio
     */
    public function createEpisode(array $data): int {
        $sql = "
            INSERT INTO episodes (series_id, season, episode_number, title, description, duration, video_path, thumbnail)
            VALUES (:series_id, :season, :episode_number, :title, :description, :duration, :video_path, :thumbnail)
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        // Actualizar total de temporadas si es necesario
        $this->updateSeasonCount($data['series_id']);
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Actualiza el contador de temporadas de una serie
     */
    private function updateSeasonCount(int $seriesId): void {
        $sql = "
            UPDATE series 
            SET total_seasons = (SELECT MAX(season) FROM episodes WHERE series_id = :id)
            WHERE id = :id2
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $seriesId, 'id2' => $seriesId]);
    }
    
    /**
     * Elimina un episodio
     */
    public function deleteEpisode(int $episodeId): bool {
        $episode = $this->getEpisode($episodeId);
        if (!$episode) {
            return false;
        }
        
        $sql = "DELETE FROM episodes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['id' => $episodeId]);
        
        if ($result) {
            $this->updateSeasonCount($episode['series_id']);
        }
        
        return $result;
    }
    
    /**
     * Actualiza un episodio
     */
    public function updateEpisode(int $episodeId, array $data): bool {
        $episode = $this->getEpisode($episodeId);
        if (!$episode) {
            return false;
        }
        
        $fields = [];
        $params = ['id' => $episodeId];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }
        
        $sql = "UPDATE episodes SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result && isset($data['season'])) {
            $this->updateSeasonCount($episode['series_id']);
        }
        
        return $result;
    }
    
    /**
     * Sincroniza las categorías de una serie
     */
    public function syncCategories(int $seriesId, array $categoryIds): void {
        $sql = "DELETE FROM series_categories WHERE series_id = :series_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['series_id' => $seriesId]);
        
        if (!empty($categoryIds)) {
            $sql = "INSERT INTO series_categories (series_id, category_id) VALUES (:series_id, :category_id)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($categoryIds as $categoryId) {
                $stmt->execute([
                    'series_id' => $seriesId,
                    'category_id' => $categoryId
                ]);
            }
        }
    }
    
    /**
     * Busca series por título o descripción
     */
    public function search(string $query, bool $includeVault = false): array {
        $query = sanitizeSearch($query);
        $vaultCondition = $includeVault ? '' : 'AND is_vault = FALSE';
        
        $sql = "
            SELECT *, MATCH(title, description) AGAINST(:query) as relevance,
                   (SELECT COUNT(*) FROM episodes WHERE series_id = series.id) as total_episodes
            FROM series
            WHERE MATCH(title, description) AGAINST(:query2)
            {$vaultCondition}
            ORDER BY relevance DESC
            LIMIT 50
        ";
        
        return $this->query($sql, ['query' => $query, 'query2' => $query]);
    }
    
    /**
     * Obtiene series de la Caja Fuerte
     */
    public function getVaultSeries(): array {
        $sql = "
            SELECT s.*, 
                   (SELECT COUNT(*) FROM episodes WHERE series_id = s.id) as total_episodes
            FROM series s
            WHERE s.is_vault = TRUE
            ORDER BY s.created_at DESC
        ";
        
        return $this->query($sql);
    }
}
