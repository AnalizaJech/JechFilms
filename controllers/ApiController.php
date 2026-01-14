<?php
/**
 * Controlador API para peticiones AJAX
 */

require_once BASE_PATH . '/models/UserList.php';
require_once BASE_PATH . '/models/Reaction.php';
require_once BASE_PATH . '/models/WatchProgress.php';
require_once BASE_PATH . '/models/Series.php';

class ApiController {
    
    /**
     * Toggle de lista
     */
    public function list(): void {
        $this->requireAuthJson();
        
        $data = $this->getJsonInput();
        $type = $data['type'] ?? '';
        $id = (int) ($data['id'] ?? 0);
        
        if (!in_array($type, ['movie', 'series']) || $id <= 0) {
            $this->json(['error' => 'Datos inválidos'], 400);
        }
        
        $listModel = new UserList();
        $result = $listModel->toggle(userId(), $type, $id);
        
        $this->json($result);
    }
    
    /**
     * Reacción (like/dislike)
     */
    public function react(): void {
        $this->requireAuthJson();
        
        $data = $this->getJsonInput();
        $type = $data['type'] ?? '';
        $id = (int) ($data['id'] ?? 0);
        $reaction = $data['reaction'] ?? '';
        
        if (!in_array($type, ['movie', 'series']) || $id <= 0 || !in_array($reaction, ['like', 'dislike'])) {
            $this->json(['error' => 'Datos inválidos'], 400);
        }
        
        $reactionModel = new Reaction();
        $result = $reactionModel->setReaction(userId(), $type, $id, $reaction);
        
        $this->json($result);
    }
    
    /**
     * Guardar progreso de reproducción
     */
    public function saveProgress(): void {
        $this->requireAuthJson();
        
        $data = $this->getJsonInput();
        $type = $data['type'] ?? ''; // 'movie' o 'episode'
        $id = (int) ($data['id'] ?? 0);
        $progress = (int) ($data['progress'] ?? 0);
        $duration = (int) ($data['duration'] ?? 0);
        
        if (!in_array($type, ['movie', 'episode']) || $id <= 0) {
            $this->json(['error' => 'Datos inválidos'], 400);
        }
        
        $progressModel = new WatchProgress();
        $result = $progressModel->saveProgress(userId(), $type, $id, $progress, $duration);
        
        $this->json(['success' => $result]);
    }
    
    /**
     * Obtener progreso de reproducción
     */
    public function getProgress(): void {
        if (!isAuthenticated()) {
            $this->json(['state' => 'play', 'progress' => 0, 'label' => 'Reproducir']);
            return;
        }
        
        $type = $_GET['type'] ?? '';
        $id = (int) ($_GET['id'] ?? 0);
        
        if (!in_array($type, ['movie', 'episode']) || $id <= 0) {
            $this->json(['error' => 'Datos inválidos'], 400);
        }
        
        $progressModel = new WatchProgress();
        $result = $progressModel->getWatchState(userId(), $type, $id);
        
        $this->json($result);
    }
    
    /**
     * Obtener episodios de una serie (para modal)
     */
    public function episodes(int $seriesId = 0): void {
        if ($seriesId <= 0) {
            $this->json(['error' => 'ID de serie inválido'], 400);
        }
        
        $seriesModel = new Series();
        $seasons = $seriesModel->getEpisodes($seriesId);
        
        // Formatear episodios para el modal
        $episodes = [];
        foreach ($seasons as $seasonNum => $seasonEpisodes) {
            foreach ($seasonEpisodes as $ep) {
                $episodes[] = [
                    'id' => $ep['id'],
                    'season' => $seasonNum,
                    'episode_number' => $ep['episode_number'],
                    'title' => $ep['title'] ?? 'Episodio ' . $ep['episode_number'],
                    'duration' => $ep['duration'] ?? null,
                    'description' => $ep['description'] ?? ''
                ];
            }
        }
        
        $this->json(['episodes' => $episodes]);
    }
    
    /**
     * Helpers
     */
    private function requireAuthJson(): void {
        if (!isAuthenticated()) {
            $this->json(['error' => 'No autorizado'], 401);
        }
    }
    
    private function getJsonInput(): array {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
    
    private function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public function index(string $action = ''): void {
        // Manejar rutas con parámetros como episodes/123
        if (preg_match('/^episodes\/(\d+)$/', $action, $matches)) {
            $this->episodes((int)$matches[1]);
            return;
        }
        
        switch ($action) {
            case 'list':
                $this->list();
                break;
            case 'react':
                $this->react();
                break;
            case 'progress/save':
                $this->saveProgress();
                break;
            case 'progress/get':
                $this->getProgress();
                break;
            case 'search':
                $this->search();
                break;
            default:
                $this->json(['error' => 'Endpoint no encontrado'], 404);
        }
    }
    
    /**
     * Búsqueda en tiempo real para autocomplete
     */
    public function search(): void {
        require_once BASE_PATH . '/models/Movie.php';
        
        $query = trim($_GET['q'] ?? '');
        
        if (strlen($query) < 2) {
            $this->json(['results' => []]);
            return;
        }
        
        $movieModel = new Movie();
        $seriesModel = new Series();
        $includeVault = function_exists('hasVaultAccess') ? hasVaultAccess() : false;
        
        $movies = $movieModel->search($query, $includeVault);
        $series = $seriesModel->search($query, $includeVault);
        
        // Formatear resultados
        $results = [];
        
        foreach (array_slice($movies, 0, 5) as $m) {
            $results[] = [
                'id' => $m['id'],
                'title' => $m['title'],
                'type' => 'movie',
                'year' => $m['year'] ?? null,
                'poster' => $m['poster'] ?? null,
                'duration' => $m['duration'] ?? null,
                'categories' => $m['categories'] ?? ''
            ];
        }
        
        foreach (array_slice($series, 0, 5) as $s) {
            $results[] = [
                'id' => $s['id'],
                'title' => $s['title'],
                'type' => 'series',
                'year' => $s['year_start'] ?? null,
                'poster' => $s['poster'] ?? null,
                'total_seasons' => $s['total_seasons'] ?? 1,
                'categories' => $s['categories'] ?? ''
            ];
        }
        
        $this->json(['results' => $results]);
    }
}
