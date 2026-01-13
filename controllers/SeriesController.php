<?php
/**
 * Controlador de Series
 * Catálogo y detalle de series con episodios
 */

require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/Category.php';
require_once BASE_PATH . '/models/Reaction.php';
require_once BASE_PATH . '/models/UserList.php';

class SeriesController {
    private Series $seriesModel;
    private Category $categoryModel;
    private Reaction $reactionModel;
    private UserList $listModel;
    
    public function __construct() {
        $this->seriesModel = new Series();
        $this->categoryModel = new Category();
        $this->reactionModel = new Reaction();
        $this->listModel = new UserList();
    }
    
    /**
     * Catálogo de series
     */
    public function index(): void {
        $series = $this->seriesModel->getAllWithCategories();
        $categories = $this->categoryModel->getParents();
        
        // Aplicar filtros simples
        $categoryFilter = get('category');
        $sortBy = get('sort', 'recent');
        
        if ($categoryFilter) {
            $series = array_filter($series, function($s) use ($categoryFilter) {
                return strpos($s['category_ids'] ?? '', (string) $categoryFilter) !== false;
            });
        }
        
        // Ordenar
        usort($series, function($a, $b) use ($sortBy) {
            return match($sortBy) {
                'popular' => ($b['view_count'] ?? 0) - ($a['view_count'] ?? 0),
                'liked' => ($b['likes_count'] ?? 0) - ($a['likes_count'] ?? 0),
                default => strtotime($b['created_at']) - strtotime($a['created_at'])
            };
        });
        
        require_once VIEWS_PATH . '/series/index.php';
    }
    
    /**
     * Detalle de una serie con episodios
     */
    public function show(int $id = 0): void {
        $series = $this->seriesModel->getWithDetails($id);
        
        if (!$series) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verificar caja fuerte
        if ($series['is_vault'] && !hasVaultAccess()) {
            flash('error', 'Este contenido requiere acceso especial.');
            redirect('vault');
        }
        
        // Obtener episodios organizados por temporada
        $seasons = $this->seriesModel->getEpisodes($id);
        
        // Reacción y lista del usuario
        $userReaction = null;
        $inList = false;
        if (isAuthenticated()) {
            $userReaction = $this->reactionModel->getUserReaction(userId(), 'series', $id);
            $inList = $this->listModel->isInList(userId(), 'series', $id);
        }
        
        require_once VIEWS_PATH . '/series/show.php';
    }
}
