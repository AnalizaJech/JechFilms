<?php
/**
 * Controlador CRUD de Películas (Admin)
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Category.php';

class MoviesController {
    private Movie $movieModel;
    private Category $categoryModel;
    
    public function __construct() {
        requireAdmin();
        $this->movieModel = new Movie();
        $this->categoryModel = new Category();
    }
    
    public function index(): void {
        $movies = $this->movieModel->getAllWithCategories(true);
        require_once VIEWS_PATH . '/admin/movies/index.php';
    }
    
    public function create(): void {
        $categories = $this->categoryModel->getParents();
        require_once VIEWS_PATH . '/admin/movies/form.php';
    }
    
    public function store(): void {
        if (!isPost() || !validateCsrf()) {
            redirect('admin/movies');
        }
        
        $data = [
            'title' => trim(post('title', '')),
            'description' => trim(post('description', '')),
            'year' => post('year') ?: null,
            'duration' => (int) post('duration') ?: null,
            'video_path' => trim(post('video_path', '')),
            'is_featured' => post('is_featured') ? 1 : 0,
            'is_vault' => post('is_vault') ? 1 : 0
        ];
        
        // Procesar poster
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = uploadFile($_FILES['poster'], 'posters', 'movie_');
        }
        
        // Procesar backdrop
        if (isset($_FILES['backdrop']) && $_FILES['backdrop']['error'] === UPLOAD_ERR_OK) {
            $data['backdrop'] = uploadFile($_FILES['backdrop'], 'backdrops', 'movie_');
        }
        
        $movieId = $this->movieModel->create($data);
        
        // Sincronizar categorías
        $categories = post('categories', []);
        if (!empty($categories)) {
            $this->movieModel->syncCategories($movieId, $categories);
        }
        
        flash('success', 'Película creada correctamente.');
        redirect('admin/movies');
    }
    
    public function edit(int $id = 0): void {
        $movie = $this->movieModel->getWithDetails($id);
        if (!$movie) {
            flash('error', 'Película no encontrada.');
            redirect('admin/movies');
        }
        
        $categories = $this->categoryModel->getParents();
        $selectedCategories = explode(',', $movie['category_ids'] ?? '');
        
        require_once VIEWS_PATH . '/admin/movies/form.php';
    }
    
    public function update(int $id = 0): void {
        if (!isPost() || !validateCsrf()) {
            redirect('admin/movies');
        }
        
        $data = [
            'title' => trim(post('title', '')),
            'description' => trim(post('description', '')),
            'year' => post('year') ?: null,
            'duration' => (int) post('duration') ?: null,
            'video_path' => trim(post('video_path', '')),
            'is_featured' => post('is_featured') ? 1 : 0,
            'is_vault' => post('is_vault') ? 1 : 0
        ];
        
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = uploadFile($_FILES['poster'], 'posters', 'movie_');
        }
        
        if (isset($_FILES['backdrop']) && $_FILES['backdrop']['error'] === UPLOAD_ERR_OK) {
            $data['backdrop'] = uploadFile($_FILES['backdrop'], 'backdrops', 'movie_');
        }
        
        $this->movieModel->update($id, $data);
        
        $categories = post('categories', []);
        $this->movieModel->syncCategories($id, $categories);
        
        flash('success', 'Película actualizada.');
        redirect('admin/movies');
    }
    
    public function delete(int $id = 0): void {
        $this->movieModel->delete($id);
        flash('success', 'Película eliminada.');
        redirect('admin/movies');
    }
}
