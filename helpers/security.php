<?php
/**
 * Funciones de seguridad
 * Jech Films - Sistema de Streaming Multimedia
 */

/**
 * Hashea una contraseña de forma segura
 */
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Verifica una contraseña contra su hash
 */
function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

/**
 * Valida que un email tenga formato correcto
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida fortaleza de contraseña
 * Mínimo 6 caracteres
 */
function isValidPassword(string $password): bool {
    return strlen($password) >= 6;
}

/**
 * Sanitiza una cadena para uso en consultas de búsqueda
 */
function sanitizeSearch(string $query): string {
    // Eliminar caracteres especiales de búsqueda
    $query = preg_replace('/[+\-<>()~*\"@]/', '', $query);
    return trim($query);
}

/**
 * Verifica que un archivo subido sea seguro
 */
function isSecureUpload(array $file): bool {
    // Verificar que no hay errores
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Verificar tamaño máximo
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return false;
    }
    
    // Verificar que es un archivo real subido
    if (!is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    return true;
}

/**
 * Genera un nombre de archivo único y seguro
 */
function secureFilename(string $originalName): string {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $safeName = preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($originalName, PATHINFO_FILENAME));
    
    return substr($safeName, 0, 20) . '_' . uniqid() . '.' . $extension;
}

/**
 * Previene ataques de path traversal
 */
function securePath(string $path): string {
    // Eliminar secuencias de directorio padre
    $path = str_replace(['../', '..\\', '..'], '', $path);
    
    // Eliminar caracteres nulos
    $path = str_replace("\0", '', $path);
    
    return $path;
}

/**
 * Limita la tasa de intentos (rate limiting básico por sesión)
 */
function checkRateLimit(string $action, int $maxAttempts = 5, int $windowSeconds = 300): bool {
    $key = 'rate_limit_' . $action;
    $now = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'start' => $now];
    }
    
    // Reiniciar si pasó la ventana de tiempo
    if ($now - $_SESSION[$key]['start'] > $windowSeconds) {
        $_SESSION[$key] = ['count' => 0, 'start' => $now];
    }
    
    // Verificar límite
    if ($_SESSION[$key]['count'] >= $maxAttempts) {
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}

/**
 * Resetea el contador de rate limiting para una acción
 */
function resetRateLimit(string $action): void {
    $key = 'rate_limit_' . $action;
    unset($_SESSION[$key]);
}
