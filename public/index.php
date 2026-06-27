<?php

session_start();

spl_autoload_register(function ($class) {
    $prefix = 'KaayDem\\';
    $base_dir = __DIR__ . '/../src/';
    
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

use KaayDem\Core\Router;

$router = new Router('/kaay_dem');

// ===== ROUTES AUTHENTIFICATION =====
$router->addRoute('GET', '/', ['KaayDem\Controllers\TripController', 'index']);
$router->addRoute('GET', '/login', ['KaayDem\Controllers\AuthController', 'showLogin']);
$router->addRoute('POST', '/login', ['KaayDem\Controllers\AuthController', 'login']);
$router->addRoute('GET', '/register', ['KaayDem\Controllers\AuthController', 'showRegister']);
$router->addRoute('POST', '/register', ['KaayDem\Controllers\AuthController', 'register']);
$router->addRoute('GET', '/logout', ['KaayDem\Controllers\AuthController', 'logout']);

// ===== ROUTES TRAJETS =====
$router->addRoute('GET', '/trips', ['KaayDem\Controllers\TripController', 'index']);
$router->addRoute('GET', '/trips/create', ['KaayDem\Controllers\TripController', 'create']);
$router->addRoute('POST', '/trips', ['KaayDem\Controllers\TripController', 'store']);
$router->addRoute('GET', '/trips/{id}', ['KaayDem\Controllers\TripController', 'show']);

// ===== ROUTES DASHBOARD =====
$router->addRoute('GET', '/dashboard', ['KaayDem\Controllers\DashboardController', 'index']);
$router->addRoute('GET', '/dashboard/reservations', ['KaayDem\Controllers\DashboardController', 'reservations']);

// ===== ROUTES ADMINISTRATEUR =====
$router->addRoute('GET', '/admin/drivers', ['KaayDem\Controllers\AdminController', 'drivers']);
$router->addRoute('GET', '/admin/reports', ['KaayDem\Controllers\AdminController', 'reports']);
$router->addRoute('GET', '/admin/statistics', ['KaayDem\Controllers\AdminController', 'statistics']);
$router->addRoute('POST', '/admin/drivers/{id}/validate', ['KaayDem\Controllers\AdminController', 'validateDriver']);
$router->addRoute('POST', '/admin/drivers/{id}/suspend', ['KaayDem\Controllers\AdminController', 'suspendDriver']);
$router->addRoute('POST', '/admin/reports/{id}/resolve', ['KaayDem\Controllers\AdminController', 'resolveReport']);
$router->addRoute('POST', '/admin/reports/{id}/dismiss', ['KaayDem\Controllers\AdminController', 'dismissReport']);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$basePath = '/kaay_dem';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

$router->dispatch($method, $uri);