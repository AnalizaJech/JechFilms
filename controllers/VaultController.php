<?php
/**
 * Controlador de Caja Fuerte
 */

require_once BASE_PATH . '/models/VaultSettings.php';
require_once BASE_PATH . '/models/Movie.php';
require_once BASE_PATH . '/models/Series.php';

class VaultController {
    private VaultSettings $vaultModel;
    
    public function __construct() {
        $this->vaultModel = new VaultSettings();
    }
    
    /**
     * Página de acceso a la caja fuerte
     */
    public function index(): void {
        // Si ya tiene acceso, mostrar contenido
        if (hasVaultAccess()) {
            $this->showContent();
            return;
        }
        
        // Verificar si está habilitada
        if (!$this->vaultModel->isEnabled()) {
            flash('error', 'La caja fuerte no está habilitada.');
            redirect('');
        }
        
        require_once VIEWS_PATH . '/vault/access.php';
    }
    
    /**
     * Verificar código de acceso
     */
    public function verify(): void {
        if (!isPost()) {
            redirect('vault');
        }
        
        $code = post('code', '');
        
        if (!checkRateLimit('vault', 5, 300)) {
            flash('error', 'Demasiados intentos. Espera 5 minutos.');
            redirect('vault');
        }
        
        if ($this->vaultModel->verifyCode($code)) {
            resetRateLimit('vault');
            grantVaultAccess();
            flash('success', 'Acceso concedido.');
            redirect('vault');
        }
        
        flash('error', 'Código incorrecto.');
        redirect('vault');
    }
    
    /**
     * Cerrar acceso a la caja fuerte
     */
    public function lock(): void {
        revokeVaultAccess();
        flash('success', 'Has salido de la caja fuerte.');
        redirect('');
    }
    
    /**
     * Muestra el contenido de la caja fuerte
     */
    private function showContent(): void {
        $movieModel = new Movie();
        $seriesModel = new Series();
        
        $movies = $movieModel->getVaultMovies();
        $series = $seriesModel->getVaultSeries();
        
        require_once VIEWS_PATH . '/vault/index.php';
    }
}
