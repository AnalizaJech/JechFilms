<?php
/**
 * Configuración de conexión a base de datos
 * Jech Films - Sistema de Streaming Multimedia
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'jech_films');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Obtiene una conexión PDO a la base de datos
 * Utiliza el patrón Singleton para reutilizar la conexión
 * 
 * @return PDO Instancia de conexión
 */
function getConnection(): PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En producción, loguear el error y mostrar mensaje genérico
            die('Error de conexión a la base de datos. Por favor, verifica la configuración.');
        }
    }
    
    return $pdo;
}
