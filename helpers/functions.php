<?php
/**
 * Funciones auxiliares generales
 * Jech Films - Sistema de Streaming Multimedia
 */

/**
 * Escapa HTML para prevenir XSS
 * Wrapper de htmlspecialchars con configuración segura
 */
function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera una URL completa a partir de una ruta relativa
 */
function url(string $path = ''): string {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Redirige a una URL y termina la ejecución
 */
function redirect(string $path): void {
    header('Location: ' . url($path));
    exit;
}

/**
 * Obtiene un valor de $_GET de forma segura
 */
function get(string $key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Obtiene un valor de $_POST de forma segura
 */
function post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Verifica si la petición es POST
 */
function isPost(): bool {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Genera un token CSRF y lo guarda en sesión
 */
function csrfToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Genera el campo hidden del token CSRF
 */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Valida el token CSRF de un formulario
 */
function validateCsrf(): bool {
    $token = post('csrf_token');
    return $token && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Establece un mensaje flash en sesión
 */
function flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Obtiene y elimina el mensaje flash de sesión
 */
function getFlash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Formatea una duración en minutos a formato legible
 */
function formatDuration(int $minutes): string {
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    
    if ($hours > 0) {
        return sprintf('%dh %02dmin', $hours, $mins);
    }
    return sprintf('%d min', $mins);
}

/**
 * Formatea una fecha a formato legible en español
 */
function formatDate(string $date): string {
    $timestamp = strtotime($date);
    $months = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    
    $day = date('j', $timestamp);
    $month = $months[(int)date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$day de $month de $year";
}

/**
 * Trunca un texto a una longitud máxima
 */
function truncate(string $text, int $length = 150): string {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Genera un slug URL-friendly a partir de un texto
 */
function slugify(string $text): string {
    // Convertir a minúsculas
    $text = mb_strtolower($text);
    
    // Reemplazar caracteres especiales
    $replacements = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ñ' => 'n', 'ü' => 'u'
    ];
    $text = strtr($text, $replacements);
    
    // Reemplazar espacios y caracteres no alfanuméricos
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    
    // Eliminar guiones al inicio y final
    return trim($text, '-');
}

/**
 * Verifica si un archivo es una imagen válida
 */
function isValidImage(array $file): bool {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    return in_array($file['type'], ALLOWED_IMAGE_TYPES);
}

/**
 * Verifica si un archivo es un video válido
 */
function isValidVideo(array $file): bool {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    return in_array($file['type'], ALLOWED_VIDEO_TYPES);
}

/**
 * Sube un archivo y retorna la ruta relativa
 */
function uploadFile(array $file, string $directory, string $prefix = ''): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . uniqid() . '_' . time() . '.' . $extension;
    $destination = UPLOADS_PATH . '/' . $directory . '/' . $filename;
    
    // Crear directorio si no existe
    $dir = dirname($destination);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/' . $directory . '/' . $filename;
    }
    
    return null;
}

/**
 * Obtiene la URL de un avatar o devuelve uno por defecto
 */
function avatarUrl(?string $avatar): string {
    if ($avatar && file_exists(PUBLIC_PATH . '/' . $avatar)) {
        return url($avatar);
    }
    return url('assets/images/default-avatar.svg');
}

/**
 * Obtiene la URL de un poster o devuelve uno por defecto
 */
function posterUrl(?string $poster): string {
    if ($poster && file_exists(PUBLIC_PATH . '/' . $poster)) {
        return url($poster);
    }
    return url('assets/images/default-poster.svg');
}
