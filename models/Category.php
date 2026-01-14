<?php
/**
 * Modelo de Categoría
 * Gestiona las operaciones de la tabla categories
 */

require_once __DIR__ . '/Model.php';

class Category extends Model {
    protected string $table = 'categories';
    
    /**
     * Obtiene todas las categorías organizadas jerárquicamente
     */
    public function getAllHierarchical(): array {
        $all = $this->all('name', 'ASC');
        
        $parents = [];
        $children = [];
        
        foreach ($all as $category) {
            if ($category['parent_id'] === null) {
                $parents[$category['id']] = $category;
                $parents[$category['id']]['children'] = [];
            } else {
                $children[] = $category;
            }
        }
        
        foreach ($children as $child) {
            if (isset($parents[$child['parent_id']])) {
                $parents[$child['parent_id']]['children'][] = $child;
            }
        }
        
        return array_values($parents);
    }
    
    /**
     * Obtiene solo las categorías padre (sin subcategorías)
     */
    public function getParents(): array {
        $sql = "SELECT * FROM {$this->table} WHERE parent_id IS NULL ORDER BY name ASC";
        return $this->query($sql);
    }
    
    /**
     * Obtiene subcategorías de una categoría padre
     */
    public function getChildren(int $parentId): array {
        return $this->findBy('parent_id', $parentId);
    }
    
    /**
     * Busca una categoría por su slug
     */
    public function findBySlug(string $slug): ?array {
        return $this->findOneBy('slug', $slug);
    }
    
    /**
     * Verifica si un slug ya existe
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch()['count'] > 0;
    }
    
    /**
     * Crea una categoría generando automáticamente el slug
     */
    public function createWithSlug(string $name, ?int $parentId = null): int {
        $slug = slugify($name);
        
        // Asegurar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $this->create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId
        ]);
    }
    
    /**
     * Obtiene las categorías con conteo de contenido
     */
    public function getAllWithCounts(): array {
        $sql = "
            SELECT c.*,
                   (SELECT COUNT(*) FROM movie_categories WHERE category_id = c.id) as movies_count,
                   (SELECT COUNT(*) FROM series_categories WHERE category_id = c.id) as series_count
            FROM categories c
            ORDER BY c.name ASC
        ";
        
        return $this->query($sql);
    }
    
    /**
     * Obtiene las categorías más populares con conteos separados
     */
    public function getPopular(int $limit = 10): array {
        $sql = "
            SELECT c.*, 
                   (SELECT COUNT(*) FROM movie_categories WHERE category_id = c.id) as movies_count,
                   (SELECT COUNT(*) FROM series_categories WHERE category_id = c.id) as series_count
            FROM categories c
            WHERE c.parent_id IS NULL
            ORDER BY (
                (SELECT COUNT(*) FROM movie_categories WHERE category_id = c.id) +
                (SELECT COUNT(*) FROM series_categories WHERE category_id = c.id)
            ) DESC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
