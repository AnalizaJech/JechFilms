<?php
/**
 * Modelo de configuración de Caja Fuerte
 * Gestiona el acceso al contenido restringido
 */

require_once __DIR__ . '/Model.php';

class VaultSettings extends Model {
    protected string $table = 'vault_settings';
    
    /**
     * Obtiene la configuración actual de la caja fuerte
     */
    public function getSettings(): ?array {
        return $this->queryOne("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1", []);
    }
    
    /**
     * Verifica si la caja fuerte está habilitada
     */
    public function isEnabled(): bool {
        $settings = $this->getSettings();
        return $settings && $settings['is_enabled'];
    }
    
    /**
     * Verifica un código de acceso
     */
    public function verifyCode(string $code): bool {
        $settings = $this->getSettings();
        if (!$settings) return false;
        return verifyPassword($code, $settings['access_code']);
    }
    
    /**
     * Actualiza el código de acceso
     */
    public function updateCode(string $newCode): bool {
        $settings = $this->getSettings();
        if (!$settings) {
            $this->create(['access_code' => hashPassword($newCode), 'is_enabled' => true]);
            return true;
        }
        return $this->update($settings['id'], ['access_code' => hashPassword($newCode)]);
    }
    
    /**
     * Habilita o deshabilita la caja fuerte
     */
    public function setEnabled(bool $enabled): bool {
        $settings = $this->getSettings();
        if (!$settings) return false;
        return $this->update($settings['id'], ['is_enabled' => $enabled]);
    }
}
