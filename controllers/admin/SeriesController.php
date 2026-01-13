<?php
/**
 * Controlador CRUD de Series (Admin)
 */

require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/Category.php';

class SeriesController {
    private Series $seriesModel;
    private Category $categoryModel;
    
    public function __construct() {
        requireAdmin();
        $this->seriesModel = new Series();
        $this->categoryModel = new Category();
    }
    
    public function index(): void {
        $series = $this->seriesModel->getAllWithCategories(true);
        require_once VIEWS_PATH . '/admin/series/index.php';
    }
    
    public function create(): void {
        $categories = $this->categoryModel->getParents();
        require_once VIEWS_PATH . '/admin/series/form.php';
    }
    
    public function store(): void {
        if (!isPost() || !validateCsrf()) redirect('admin/series');
        
        $data = [
            'title' => trim(post('title', '')),
            'description' => trim(post('description', '')),
            'year_start' => post('year_start') ?: null,
            'year_end' => post('year_end') ?: null,
            'total_seasons' => (int) post('total_seasons', 1),
            'is_featured' => post('is_featured') ? 1 : 0,
            'is_vault' => post('is_vault') ? 1 : 0
        ];
        
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = uploadFile($_FILES['poster'], 'posters', 'series_');
        }
        if (isset($_FILES['backdrop']) && $_FILES['backdrop']['error'] === UPLOAD_ERR_OK) {
            $data['backdrop'] = uploadFile($_FILES['backdrop'], 'backdrops', 'series_');
        }
        
        $seriesId = $this->seriesModel->create($data);
        $this->seriesModel->syncCategories($seriesId, post('categories', []));
        
        flash('success', 'Serie creada correctamente.');
        redirect('admin/series');
    }
    
    public function edit(int $id = 0): void {
        $series = $this->seriesModel->getWithDetails($id);
        if (!$series) {
            flash('error', 'Serie no encontrada.');
            redirect('admin/series');
        }
        
        $categories = $this->categoryModel->getParents();
        $selectedCategories = explode(',', $series['category_ids'] ?? '');
        $seasons = $this->seriesModel->getEpisodes($id);
        
        require_once VIEWS_PATH . '/admin/series/form.php';
    }
    
    public function update(int $id = 0): void {
        if (!isPost() || !validateCsrf()) redirect('admin/series');
        
        $data = [
            'title' => trim(post('title', '')),
            'description' => trim(post('description', '')),
            'year_start' => post('year_start') ?: null,
            'year_end' => post('year_end') ?: null,
            'total_seasons' => (int) post('total_seasons', 1),
            'is_featured' => post('is_featured') ? 1 : 0,
            'is_vault' => post('is_vault') ? 1 : 0
        ];
        
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = uploadFile($_FILES['poster'], 'posters', 'series_');
        }
        if (isset($_FILES['backdrop']) && $_FILES['backdrop']['error'] === UPLOAD_ERR_OK) {
            $data['backdrop'] = uploadFile($_FILES['backdrop'], 'backdrops', 'series_');
        }
        
        $this->seriesModel->update($id, $data);
        $this->seriesModel->syncCategories($id, post('categories', []));
        
        flash('success', 'Serie actualizada.');
        redirect('admin/series');
    }
    
    public function delete(int $id = 0): void {
        $this->seriesModel->delete($id);
        flash('success', 'Serie eliminada.');
        redirect('admin/series');
    }
    
    // Gestión de episodios
    public function addEpisode(int $seriesId = 0): void {
        if (!isPost() || !validateCsrf()) redirect('admin/series/edit/' . $seriesId);
        
        $data = [
            'series_id' => $seriesId,
            'season' => (int) post('season', 1),
            'episode_number' => (int) post('episode_number', 1),
            'title' => trim(post('ep_title', '')),
            'description' => trim(post('ep_description', '')),
            'duration' => (int) post('ep_duration') ?: null,
            'video_path' => trim(post('ep_video_path', '')),
            'thumbnail' => null
        ];
        
        if (isset($_FILES['ep_thumbnail']) && $_FILES['ep_thumbnail']['error'] === UPLOAD_ERR_OK) {
            $data['thumbnail'] = uploadFile($_FILES['ep_thumbnail'], 'thumbnails', 'ep_');
        }
        
        $this->seriesModel->createEpisode($data);
        flash('success', 'Episodio agregado.');
        redirect('admin/series/edit/' . $seriesId);
    }
    
    public function deleteEpisode(int $episodeId = 0): void {
        $episode = $this->seriesModel->getEpisode($episodeId);
        if ($episode) {
            $this->seriesModel->deleteEpisode($episodeId);
            flash('success', 'Episodio eliminado.');
            redirect('admin/series/edit/' . $episode['series_id']);
        }
        redirect('admin/series');
    }
}
