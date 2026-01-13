<?php
/**
 * Controlador del Reproductor
 * Reproducción de películas y episodios
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/View.php';

class PlayerController {
    private Movie $movieModel;
    private Series $seriesModel;
    private View $viewModel;
    
    public function __construct() {
        $this->movieModel = new Movie();
        $this->seriesModel = new Series();
        $this->viewModel = new View();
    }
    
    /**
     * Reproduce una película
     */
    public function movie(int $id = 0): void {
        $movie = $this->movieModel->find($id);
        
        if (!$movie) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verificar caja fuerte
        if ($movie['is_vault'] && !hasVaultAccess()) {
            flash('error', 'Acceso denegado.');
            redirect('vault');
        }
        
        // Registrar visualización
        $viewId = $this->viewModel->record('movie', $id, userId());
        
        $content = $movie;
        $contentType = 'movie';
        $nextContent = null;
        
        require_once VIEWS_PATH . '/player/watch.php';
    }
    
    /**
     * Reproduce un episodio
     */
    public function episode(int $id = 0): void {
        $episode = $this->seriesModel->getEpisode($id);
        
        if (!$episode) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verificar si la serie es de caja fuerte
        $series = $this->seriesModel->find($episode['series_id']);
        if ($series['is_vault'] && !hasVaultAccess()) {
            flash('error', 'Acceso denegado.');
            redirect('vault');
        }
        
        // Registrar visualización
        $viewId = $this->viewModel->record('episode', $id, userId());
        
        // Obtener siguiente episodio
        $nextContent = $this->seriesModel->getNextEpisode(
            $episode['series_id'],
            $episode['season'],
            $episode['episode_number']
        );
        
        $content = $episode;
        $contentType = 'episode';
        
        require_once VIEWS_PATH . '/player/watch.php';
    }
    
    /**
     * Alias para compatibilidad de rutas
     */
    public function index(string $type = '', int $id = 0): void {
        if ($type === 'movie') {
            $this->movie($id);
        } elseif ($type === 'episode') {
            $this->episode($id);
        } else {
            redirect('');
        }
    }
}
