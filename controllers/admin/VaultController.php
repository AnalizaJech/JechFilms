<?php
/**
 * Controlador de Caja Fuerte (Admin)
 */

require_once BASE_PATH . '/models/VaultSettings.php';
require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';

class VaultController {
    private VaultSettings $vaultModel;
    
    public function __construct() {
        requireAdmin();
        $this->vaultModel = new VaultSettings();
    }
    
    public function index(): void {
        $settings = $this->vaultModel->getSettings();
        
        $movieModel = new Movie();
        $seriesModel = new Series();
        
        $movies = $movieModel->getVaultMovies();
        $series = $seriesModel->getVaultSeries();
        
        require_once VIEWS_PATH . '/admin/vault/index.php';
    }
    
    public function updateCode(): void {
        if (!isPost() || !validateCsrf()) {
            redirect('admin/vault');
        }
        
        $newCode = post('new_code', '');
        $confirmCode = post('confirm_code', '');
        
        if (strlen($newCode) < 4) {
            flash('error', 'El código debe tener al menos 4 caracteres.');
            redirect('admin/vault');
        }
        
        if ($newCode !== $confirmCode) {
            flash('error', 'Los códigos no coinciden.');
            redirect('admin/vault');
        }
        
        $this->vaultModel->updateCode($newCode);
        flash('success', 'Código de acceso actualizado.');
        redirect('admin/vault');
    }
    
    public function toggle(): void {
        if (!isPost() || !validateCsrf()) {
            redirect('admin/vault');
        }
        
        $settings = $this->vaultModel->getSettings();
        $this->vaultModel->setEnabled(!$settings['is_enabled']);
        
        flash('success', 'Estado de la caja fuerte actualizado.');
        redirect('admin/vault');
    }
}
