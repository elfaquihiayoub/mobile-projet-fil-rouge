<?php

session_start();

require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';

foreach (glob(__DIR__ . '/../app/models/*.php') as $file) {
    require $file;
}
foreach (glob(__DIR__ . '/../app/controllers/*.php') as $file) {
    require $file;
}

$routes = [
    'dashboard' => DashboardController::class,
    'adherent' => AdherentController::class,
    'rayon' => RayonController::class,
    'livre' => LivreController::class,
    'emprunt' => EmpruntController::class,
    'retour' => RetourController::class,
];

$controllerKey = strtolower($_GET['controller'] ?? 'dashboard');
$action = $_GET['action'] ?? 'index';
$class = $routes[$controllerKey] ?? DashboardController::class;
$controller = new $class();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Page introuvable.';
    exit;
}

$controller->$action();
