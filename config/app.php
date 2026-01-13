<?php
/**
 * Configuración general de la aplicación
 * Jech Films - Sistema de Streaming Multimedia
 */

// Zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de errores (cambiar a false en producción)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Rutas base del sistema
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', BASE_PATH . '/views');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');
define('MEDIA_PATH', BASE_PATH . '/media');

// URL base (ajustar según tu servidor local)
define('BASE_URL', 'http://localhost:8000');

// Configuración de sesiones
define('SESSION_NAME', 'jech_films_session');
define('SESSION_LIFETIME', 7200); // 2 horas

// Configuración de uploads
define('MAX_UPLOAD_SIZE', 1024 * 1024 * 500); // 500MB para videos
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/ogg', 'video/x-matroska']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Configuración de la aplicación
define('APP_NAME', 'Jech Films');
define('APP_VERSION', '1.0.0');

// Paginación por defecto
define('ITEMS_PER_PAGE', 20);

// Configuración de rankings
define('RANKING_WEEK_DAYS', 7); // Días para calcular ranking semanal
