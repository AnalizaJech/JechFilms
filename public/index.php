<?php
/**
 * Punto de entrada principal de la aplicación
 * Jech Films - Sistema de Streaming Multimedia
 * 
 * Todas las peticiones pasan por este archivo
 */

// Cargar configuración
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Cargar helpers
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/security.php';

// Iniciar sesión
initSession();

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';
$path = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));
$path = trim($path, '/');

// Si no hay ruta, ir a inicio
if (empty($path)) {
    $path = 'home';
}

// Dividir en segmentos para el router
$segments = explode('/', $path);
$controller = $segments[0] ?? 'home';
$action = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Router simplificado - mapea rutas a controladores
// Formato: 'ruta' => 'Controlador' o ['Controlador', 'método GET', 'método POST']
$routes = [
    // Rutas públicas de autenticación
    'home' => 'HomeController',
    'login' => ['AuthController', 'index', 'login'],     // GET: index, POST: login  
    'register' => ['AuthController', 'register', 'store'], // GET: register, POST: store
    'logout' => ['AuthController', 'logout'],
    
    // Rutas de usuario
    'movies' => 'MovieController',
    'series' => 'SeriesController',
    'watch' => 'PlayerController',
    'list' => 'ListController',
    'my-list' => 'ListController',
    'profile' => 'ProfileController',
    'search' => 'SearchController',
    
    // Caja Fuerte
    'vault' => 'VaultController',
    
    // Panel de administración
    'admin' => 'admin/DashboardController',
    
    // API interna para AJAX
    'api' => 'ApiController',
];

// Determinar el controlador a usar
if ($controller === 'admin' && isset($segments[1])) {
    // Rutas del panel de administración
    $adminController = $segments[1];
    $action = $segments[2] ?? 'index';
    $params = array_slice($segments, 3);
    
    $adminRoutes = [
        'dashboard' => 'admin/DashboardController',
        'users' => 'admin/UsersController',
        'movies' => 'admin/MoviesController',
        'series' => 'admin/SeriesController',
        'categories' => 'admin/CategoriesController',
        'vault' => 'admin/VaultController',
        'featured' => 'admin/FeaturedController',
    ];
    
    $controllerClass = $adminRoutes[$adminController] ?? null;
} else {
    $routeConfig = $routes[$controller] ?? null;
    
    // Si la ruta es un array, tiene controlador y acción predefinidos
    if (is_array($routeConfig)) {
        $controllerClass = $routeConfig[0];
        
        // Determinar la acción según el método HTTP
        // Formato: ['Controlador', 'método GET', 'método POST' (opcional)]
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($routeConfig[2])) {
            // POST con método específico definido
            $action = $routeConfig[2];
        } elseif ($action === 'index' || $action === $controller) {
            // GET o sin acción específica en la URL
            $action = $routeConfig[1];
        }
        // Si hay una acción diferente en la URL (ej: /login/algo), se mantiene
    } else {
        $controllerClass = $routeConfig;
    }
}

// Si no existe la ruta, mostrar 404
if ($controllerClass === null) {
    http_response_code(404);
    require_once VIEWS_PATH . '/errors/404.php';
    exit;
}

// Cargar y ejecutar el controlador
$controllerFile = BASE_PATH . '/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    require_once VIEWS_PATH . '/errors/404.php';
    exit;
}

require_once $controllerFile;

// Obtener el nombre de la clase (sin la ruta)
$className = basename($controllerClass);

// Crear instancia del controlador
$controllerInstance = new $className();

// Verificar si existe el método
if (!method_exists($controllerInstance, $action)) {
    // Intentar con index y pasar action como parámetro
    if (method_exists($controllerInstance, 'index')) {
        array_unshift($params, $action);
        $action = 'index';
    } else {
        http_response_code(404);
        require_once VIEWS_PATH . '/errors/404.php';
        exit;
    }
}

// Ejecutar la acción del controlador
call_user_func_array([$controllerInstance, $action], $params);
