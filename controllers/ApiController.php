<?php
/**
 * Controlador API para peticiones AJAX
 */

require_once BASE_PATH . '/models/UserList.php';
require_once BASE_PATH . '/models/Reaction.php';

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
        if ($action === 'list') {
            $this->list();
        } elseif ($action === 'react') {
            $this->react();
        } else {
            $this->json(['error' => 'Endpoint no encontrado'], 404);
        }
    }
}
