<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $path, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../../views/{$path}.php";
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function abort(int $code = 404): void
    {
        http_response_code($code);
        echo "{$code} | Access denied";
        exit;
    }
}
