<?php
/**
 * Controlador de Películas
 * Catálogo y detalle de películas
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Category.php';
require_once BASE_PATH . '/models/Reaction.php';
require_once BASE_PATH . '/models/UserList.php';

class MovieController {
    private Movie $movieModel;
    private Category $categoryModel;
    private Reaction $reactionModel;
    private UserList $listModel;
    
    public function __construct() {
        $this->movieModel = new Movie();
        $this->categoryModel = new Category();
        $this->reactionModel = new Reaction();
        $this->listModel = new UserList();
    }
    
    /**
     * Catálogo de películas
     */
    public function index(): void {
        $page = max(1, (int) get('page', 1));
        $filters = [
            'category' => get('category'),
            'year' => get('year'),
            'sort' => get('sort', 'recent')
        ];
        
        $result = $this->movieModel->getFiltered($filters, $page);
        $movies = $result['data'];
        $pagination = $result;
        
        $categories = $this->categoryModel->getParents();
        
        require_once VIEWS_PATH . '/movies/index.php';
    }
    
    /**
     * Detalle de una película
     */
    public function show(int $id = 0): void {
        $movie = $this->movieModel->getWithDetails($id);
        
        if (!$movie) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verificar si es contenido de caja fuerte
        if ($movie['is_vault'] && !hasVaultAccess()) {
            flash('error', 'Este contenido requiere acceso especial.');
            redirect('vault');
        }
        
        // Obtener reacción del usuario si está logueado
        $userReaction = null;
        $inList = false;
        if (isAuthenticated()) {
            $userReaction = $this->reactionModel->getUserReaction(userId(), 'movie', $id);
            $inList = $this->listModel->isInList(userId(), 'movie', $id);
        }
        
        // Películas relacionadas (misma categoría)
        $relatedMovies = [];
        if (!empty($movie['category_ids'])) {
            $categoryIds = explode(',', $movie['category_ids']);
            $relatedMovies = $this->movieModel->getByCategory((int) $categoryIds[0], 6);
            $relatedMovies = array_filter($relatedMovies, fn($m) => $m['id'] !== $id);
        }
        
        require_once VIEWS_PATH . '/movies/show.php';
    }
}
