<?php
/**
 * Controlador de Búsqueda
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';

class SearchController {
    
    public function index(): void {
        $query = trim(get('q', ''));
        
        $movies = [];
        $series = [];
        
        if (strlen($query) >= 2) {
            $movieModel = new Movie();
            $seriesModel = new Series();
            
            $includeVault = hasVaultAccess();
            
            $movies = $movieModel->search($query, $includeVault);
            $series = $seriesModel->search($query, $includeVault);
        }
        
        require_once VIEWS_PATH . '/search/index.php';
    }
}
