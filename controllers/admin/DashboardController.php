<?php
/**
 * Controlador del Dashboard Admin
 */

require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/View.php';

class DashboardController {
    
    public function __construct() {
        requireAdmin();
    }
    
    public function index(): void {
        $userModel = new User();
        $movieModel = new Movie();
        $seriesModel = new Series();
        $viewModel = new View();
        
        $stats = [
            'users' => $userModel->count(),
            'movies' => $movieModel->count(),
            'series' => $seriesModel->count(),
            'views' => $viewModel->getSystemStats()
        ];
        
        $recentMovies = $movieModel->getRecent(5);
        $recentSeries = $seriesModel->getRecent(5);
        
        require_once VIEWS_PATH . '/admin/dashboard.php';
    }
}
