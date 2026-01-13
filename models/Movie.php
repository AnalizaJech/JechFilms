<?php
/**
 * Modelo de Película
 * Gestiona las operaciones de la tabla movies
 */

require_once __DIR__ . '/Model.php';

class Movie extends Model {
    protected string $table = 'movies';
    
    /**
     * Obtiene películas con sus categorías
     */
    public function getAllWithCategories(bool $includeVault = false): array {
        $vaultCondition = $includeVault ? '' : 'WHERE m.is_vault = FALSE';
        
        $sql = "
            SELECT m.*, 
                   GROUP_CONCAT(c.name SEPARATOR ', ') as categories
            FROM movies m
            LEFT JOIN movie_categories mc ON m.id = mc.movie_id
            LEFT JOIN categories c ON mc.category_id = c.id
            {$vaultCondition}
            GROUP BY m.id
            ORDER BY m.created_at DESC
        ";
        
        return $this->query($sql);
    }
    
    /**
     * Obtiene una película con todas sus relaciones
     */
    public function getWithDetails(int $id): ?array {
        $sql = "
            SELECT m.*,
                   GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories,
                   GROUP_CONCAT(DISTINCT c.id SEPARATOR ',') as category_ids,
                   (SELECT COUNT(*) FROM views WHERE content_type = 'movie' AND content_id = m.id) as views_count,
                   (SELECT COUNT(*) FROM reactions WHERE content_type = 'movie' AND content_id = m.id AND reaction = 'like') as likes_count,
                   (SELECT COUNT(*) FROM reactions WHERE content_type = 'movie' AND content_id = m.id AND reaction = 'dislike') as dislikes_count
            FROM movies m
            LEFT JOIN movie_categories mc ON m.id = mc.movie_id
            LEFT JOIN categories c ON mc.category_id = c.id
            WHERE m.id = :id
            GROUP BY m.id
        ";
        
        return $this->queryOne($sql, ['id' => $id]);
    }
    
    /**
     * Obtiene películas destacadas
     */
    public function getFeatured(int $limit = 5): array {
        $sql = "
            SELECT * FROM movies 
            WHERE is_featured = TRUE AND is_vault = FALSE
            ORDER BY RAND()
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene películas más vistas de la semana
     */
    public function getWeeklyTrending(int $limit = 10): array {
        $sql = "
            SELECT m.*, COUNT(v.id) as view_count
            FROM movies m
            INNER JOIN views v ON v.content_type = 'movie' AND v.content_id = m.id
            WHERE v.watched_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                  AND m.is_vault = FALSE
            GROUP BY m.id
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
     * Obtiene películas más gustadas
     */
    public function getMostLiked(int $limit = 10): array {
        $sql = "
            SELECT m.*, COUNT(r.id) as like_count
            FROM movies m
            INNER JOIN reactions r ON r.content_type = 'movie' AND r.content_id = m.id AND r.reaction = 'like'
            WHERE m.is_vault = FALSE
            GROUP BY m.id
            ORDER BY like_count DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene películas recientes
     */
    public function getRecent(int $limit = 10): array {
        $sql = "
            SELECT * FROM movies 
            WHERE is_vault = FALSE
            ORDER BY created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene películas por categoría
     */
    public function getByCategory(int $categoryId, int $limit = 20): array {
        $sql = "
            SELECT m.* FROM movies m
            INNER JOIN movie_categories mc ON m.id = mc.movie_id
            WHERE mc.category_id = :category_id AND m.is_vault = FALSE
            ORDER BY m.created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Busca películas por título o descripción
     */
    public function search(string $query, bool $includeVault = false): array {
        $query = sanitizeSearch($query);
        $vaultCondition = $includeVault ? '' : 'AND is_vault = FALSE';
        
        $sql = "
            SELECT *, MATCH(title, description) AGAINST(:query) as relevance
            FROM movies
            WHERE MATCH(title, description) AGAINST(:query2)
            {$vaultCondition}
            ORDER BY relevance DESC
            LIMIT 50
        ";
        
        return $this->query($sql, ['query' => $query, 'query2' => $query]);
    }
    
    /**
     * Obtiene películas de la Caja Fuerte
     */
    public function getVaultMovies(): array {
        return $this->findBy('is_vault', true);
    }
    
    /**
     * Sincroniza las categorías de una película
     */
    public function syncCategories(int $movieId, array $categoryIds): void {
        // Eliminar categorías existentes
        $sql = "DELETE FROM movie_categories WHERE movie_id = :movie_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['movie_id' => $movieId]);
        
        // Insertar nuevas categorías
        if (!empty($categoryIds)) {
            $sql = "INSERT INTO movie_categories (movie_id, category_id) VALUES (:movie_id, :category_id)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($categoryIds as $categoryId) {
                $stmt->execute([
                    'movie_id' => $movieId,
                    'category_id' => $categoryId
                ]);
            }
        }
    }
    
    /**
     * Obtiene películas con paginación y filtros
     */
    public function getFiltered(array $filters = [], int $page = 1, int $perPage = ITEMS_PER_PAGE): array {
        $where = ['m.is_vault = FALSE'];
        $params = [];
        $joins = '';
        
        // Filtro por categoría
        if (!empty($filters['category'])) {
            $joins .= ' INNER JOIN movie_categories mc ON m.id = mc.movie_id';
            $where[] = 'mc.category_id = :category';
            $params['category'] = $filters['category'];
        }
        
        // Filtro por año
        if (!empty($filters['year'])) {
            $where[] = 'm.year = :year';
            $params['year'] = $filters['year'];
        }
        
        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        
        // Contar total
        $countSql = "SELECT COUNT(DISTINCT m.id) as total FROM movies m {$joins} WHERE {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Obtener registros
        $orderBy = match($filters['sort'] ?? 'recent') {
            'popular' => '(SELECT COUNT(*) FROM views WHERE content_type = "movie" AND content_id = m.id) DESC',
            'liked' => '(SELECT COUNT(*) FROM reactions WHERE content_type = "movie" AND content_id = m.id AND reaction = "like") DESC',
            'year' => 'm.year DESC',
            default => 'm.created_at DESC'
        };
        
        $sql = "
            SELECT DISTINCT m.* FROM movies m
            {$joins}
            WHERE {$whereClause}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
