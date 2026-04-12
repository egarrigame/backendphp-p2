<?php

declare(strict_types=1);

class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);

        $basePath = __DIR__ . '/../app/views/';

        // Intento 1: nombre tal cual
        $viewPath = $basePath . $view . '.php';

        // Intento 2: reemplazar - por _
        if (!file_exists($viewPath)) {
            $viewAlt = str_replace('-', '_', $view);
            $viewPath = $basePath . $viewAlt . '.php';
        }

        if (!file_exists($viewPath)) {
            http_response_code(404);
            die("La vista {$view} no existe.");
        }

        $layoutPath = $basePath . 'layouts/' . $layout . '.php';

        if (!file_exists($layoutPath)) {
            http_response_code(500);
            die("El layout {$layout} no existe.");
        }

        require $layoutPath;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }
}