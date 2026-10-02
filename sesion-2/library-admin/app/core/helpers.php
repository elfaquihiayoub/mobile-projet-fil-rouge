<?php

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $controller = 'dashboard', string $action = 'index', array $params = []): string
{
    $query = array_merge(['controller' => $controller, 'action' => $action], $params);
    return 'index.php?' . http_build_query($query);
}

function redirect(string $controller, string $action = 'index', array $params = []): never
{
    header('Location: ' . url($controller, $action, $params));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            flash('error', 'Session expirée. Veuillez réessayer.');
            redirect('dashboard');
        }
    }
}

function flash(string $type, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$type] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$type] ?? null;
    unset($_SESSION['flash'][$type]);
    return $value;
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layouts/layout.php';
}
