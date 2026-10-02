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

$controllerKey = strtolower($_GET['controller'] ?? 'dashboard');
$resource = strtolower($_GET['resource'] ?? '');

if ($controllerKey === 'api') {
    if ($resource !== 'rayon') {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ressource introuvable.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $apiController = new RayonApiController();
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $apiController->show($id);
            } else {
                $apiController->index();
            }
            exit;
        case 'POST':
            $apiController->store();
            exit;
        case 'PUT':
            $apiController->update($id ?? 0);
            exit;
        case 'DELETE':
            $apiController->delete($id ?? 0);
            exit;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Methode HTTP non autorisee.'], JSON_UNESCAPED_UNICODE);
            exit;
    }
}

$routes = [
    'dashboard' => DashboardController::class,
    'adherent' => AdherentController::class,
    'rayon' => RayonController::class,
    'livre' => LivreController::class,
    'emprunt' => EmpruntController::class,
    'retour' => RetourController::class,
];

$action = $_GET['action'] ?? 'index';
$class = $routes[$controllerKey] ?? DashboardController::class;
$controller = new $class();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Page introuvable.';
    exit;
}

$controller->$action();
