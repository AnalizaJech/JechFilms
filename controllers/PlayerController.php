<?php
/**
 * Controlador del Reproductor
 * Reproducción de películas y episodios
 * Con sistema de progreso (Continuar viendo)
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/View.php';
require_once BASE_PATH . '/models/WatchProgress.php';

class PlayerController {
    private Movie $movieModel;
    private Series $seriesModel;
    private View $viewModel;
    private WatchProgress $progressModel;
    
    public function __construct() {
        $this->movieModel = new Movie();
        $this->seriesModel = new Series();
        $this->viewModel = new View();
        $this->progressModel = new WatchProgress();
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
        
        // Obtener progreso guardado (si el usuario está logueado)
        $watchProgress = null;
        if (isAuthenticated()) {
            try {
                $watchProgress = $this->progressModel->getProgress(userId(), 'movie', $id);
            } catch (Exception $e) {
                // Silenciosamente ignorar error si la tabla no existe
            }
        }
        
        $content = $movie;
        $contentType = 'movie';
        $nextContent = null;
        
        require_once VIEWS_PATH . '/player/watch.php';
    }

    public function series(int $seriesId = 0, int $episodeId = 0): void {
        if ($episodeId > 0) {
            $this->episode($episodeId);
            return;
        }
        
        // Si solo tenemos ID de serie, buscar el primer episodio
        $firstEp = $this->seriesModel->getFirstEpisode($seriesId);
        
        if ($firstEp) {
            $this->episode($firstEp['id']);
        } else {
            // Si la serie no tiene episodios, redirigir a lista
            redirect('series');
        }
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
        
        // Obtener progreso guardado (si el usuario está logueado)
        $watchProgress = null;
        if (isAuthenticated()) {
            try {
                $watchProgress = $this->progressModel->getProgress(userId(), 'episode', $id);
            } catch (Exception $e) {
                // Silenciosamente ignorar error si la tabla no existe
            }
        }
        
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

