<?php

namespace App\Core;

abstract class Controller
{
    /**
     * Render a view with the main layout
     */
    protected function view(string $path, array $data = [], string $layout = 'main'): void
    {
        ViewRenderer::render($path, $data, $layout);
    }

    /**
     * Render a view without any layout
     */
    protected function viewPlain(string $path, array $data = []): void
    {
        ViewRenderer::renderPlain($path, $data);
    }

    /**
     * Render a partial view
     */
    protected function partial(string $partial, array $data = []): string
    {
        return ViewRenderer::partial($partial, $data);
    }

    /**
     * Redirect to another page
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Abort with error code
     */
    protected function abort(int $code = 404): void
    {
        http_response_code($code);
        echo "{$code} | Access denied";
        exit;
    }
}
