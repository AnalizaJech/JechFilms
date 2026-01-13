<?php
/**
 * Funciones de autenticación y autorización
 * Jech Films - Sistema de Streaming Multimedia
 */

/**
 * Inicia la sesión con configuración segura
 */
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

/**
 * Verifica si el usuario está autenticado
 */
function isAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica si el usuario actual es administrador
 */
function isAdmin(): bool {
    return isAuthenticated() && ($_SESSION['user_role'] ?? '') === 'admin';
}

/**
 * Obtiene el ID del usuario autenticado
 */
function userId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtiene los datos del usuario autenticado desde la sesión
 */
function currentUser(): ?array {
    if (!isAuthenticated()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['user_username'],
        'email' => $_SESSION['user_email'],
        'avatar' => $_SESSION['user_avatar'] ?? null,
        'role' => $_SESSION['user_role']
    ];
}

/**
 * Inicia sesión para un usuario
 * Regenera el ID de sesión por seguridad
 */
function loginUser(array $user): void {
    session_regenerate_id(true);
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_avatar'] = $user['avatar'];
    $_SESSION['user_role'] = $user['role'];
}

/**
 * Cierra la sesión del usuario
 */
function logoutUser(): void {
    $_SESSION = [];
    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
}

/**
 * Requiere que el usuario esté autenticado
 * Redirige a login si no lo está
 */
function requireAuth(): void {
    if (!isAuthenticated()) {
        flash('error', 'Debes iniciar sesión para acceder a esta página.');
        redirect('login');
    }
}

/**
 * Requiere que el usuario sea administrador
 * Redirige a inicio si no tiene permisos
 */
function requireAdmin(): void {
    requireAuth();
    
    if (!isAdmin()) {
        flash('error', 'No tienes permisos para acceder a esta sección.');
        redirect('');
    }
}

/**
 * Verifica si el usuario tiene acceso a la Caja Fuerte
 */
function hasVaultAccess(): bool {
    return isset($_SESSION['vault_access']) && $_SESSION['vault_access'] === true;
}

/**
 * Otorga acceso a la Caja Fuerte
 */
function grantVaultAccess(): void {
    $_SESSION['vault_access'] = true;
    $_SESSION['vault_access_time'] = time();
}

/**
 * Revoca el acceso a la Caja Fuerte
 */
function revokeVaultAccess(): void {
    unset($_SESSION['vault_access']);
    unset($_SESSION['vault_access_time']);
}

/**
 * Actualiza los datos del usuario en sesión
 * Útil después de editar el perfil
 */
function refreshUserSession(array $user): void {
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_avatar'] = $user['avatar'];
}
