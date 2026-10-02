<?php
require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';
foreach (glob(__DIR__ . '/../app/models/*.php') as $file) {
    require $file;
}
foreach (glob(__DIR__ . '/../app/controllers/*.php') as $file) {
    require $file;
}

ob_start();
$controller = new RayonApiController();
$controller->index();
$output = ob_get_clean();
$payload = json_decode($output, true);

if (!is_array($payload) || !($payload['success'] ?? false)) {
    fwrite(STDERR, "Rayon API smoke test failed\n");
    exit(1);
}

echo json_encode(['ok' => true, 'count' => count($payload['data'] ?? [])], JSON_UNESCAPED_UNICODE) . PHP_EOL;
