<?php
/**
 * Controlador de Inicio
 * Página principal con contenido destacado y rankings
 */

require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';
require_once BASE_PATH . '/models/Category.php';

class HomeController {
    private Movie $movieModel;
    private Series $seriesModel;
    private Category $categoryModel;
    
    public function __construct() {
        $this->movieModel = new Movie();
        $this->seriesModel = new Series();
        $this->categoryModel = new Category();
    }
    
    /**
     * Página de inicio
     */
    public function index(): void {
        // Contenido destacado para el hero
        $featuredMovies = $this->movieModel->getFeatured(5);
        $featuredSeries = $this->seriesModel->getFeatured(5);
        $featured = array_merge($featuredMovies, $featuredSeries);
        shuffle($featured);
        $heroContent = $featured[0] ?? null;
        
        // Rankings semanales
        $trendingMovies = $this->movieModel->getWeeklyTrending(10);
        $trendingSeries = $this->seriesModel->getWeeklyTrending(10);
        
        // Más gustados
        $likedMovies = $this->movieModel->getMostLiked(10);
        $likedSeries = $this->seriesModel->getMostLiked(10);
        
        // Recientes
        $recentMovies = $this->movieModel->getRecent(10);
        $recentSeries = $this->seriesModel->getRecent(10);
        
        // Categorías populares
        $categories = $this->categoryModel->getPopular(6);
        
        require_once VIEWS_PATH . '/home/index.php';
    }
}
