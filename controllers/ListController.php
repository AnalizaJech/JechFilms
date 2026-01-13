<?php
/**
 * Controlador de Mi Lista
 */

require_once BASE_PATH . '/models/UserList.php';

class ListController {
    private UserList $listModel;
    
    public function __construct() {
        $this->listModel = new UserList();
    }
    
    /**
     * Muestra la lista del usuario
     */
    public function index(): void {
        requireAuth();
        
        $items = $this->listModel->getUserList(userId());
        
        require_once VIEWS_PATH . '/list/index.php';
    }
}
