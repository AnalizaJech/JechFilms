<?php
/**
 * Modelo base abstracto
 * Proporciona funcionalidad común para todos los modelos
 */

abstract class Model {
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    
    public function __construct() {
        $this->db = getConnection();
    }
    
    /**
     * Encuentra un registro por su ID
     */
    public function find(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obtiene todos los registros
     */
    public function all(string $orderBy = 'id', string $direction = 'DESC'): array {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene registros con paginación
     */
    public function paginate(int $page = 1, int $perPage = ITEMS_PER_PAGE, string $orderBy = 'id', string $direction = 'DESC'): array {
        $offset = ($page - 1) * $perPage;
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        
        // Obtener total de registros
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $total = $this->db->query($countSql)->fetch()['total'];
        
        // Obtener registros de la página
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
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
    
    /**
     * Crea un nuevo registro
     */
    public function create(array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un registro existente
     */
    public function update(int $id, array $data): bool {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $sets);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Elimina un registro
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Busca registros por una columna específica
     */
    public function findBy(string $column, $value): array {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Busca un único registro por una columna
     */
    public function findOneBy(string $column, $value): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Cuenta el total de registros
     */
    public function count(): int {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        return (int) $this->db->query($sql)->fetch()['total'];
    }
    
    /**
     * Ejecuta una consulta personalizada
     */
    protected function query(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Ejecuta una consulta que retorna un solo registro
     */
    protected function queryOne(string $sql, array $params = []): ?array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
