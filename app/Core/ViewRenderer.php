<?php

namespace App\Core;

class ViewRenderer
{
    private static string $layoutPath = __DIR__ . '/../../views/layouts/main.php';

    /**
     * Render a view with layout
     * 
     * @param string $view Path to view file (e.g., 'dashboard', 'users/index')
     * @param array $data Data to pass to the view
     * @param string $layout Layout file to use (e.g., 'main')
     */
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extract data for use in views
        extract($data);

        // Start output buffering to capture view content
        ob_start();

        // Include the view file
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            ob_end_clean();
            http_response_code(500);
            echo "View file not found: {$view}";
            return;
        }

        include $viewPath;
        $content = ob_get_clean();

        // Pass content to layout
        $layoutPath = __DIR__ . '/../../views/layouts/' . $layout . '.php';

        if (!file_exists($layoutPath)) {
            echo $content;
            return;
        }

        include $layoutPath;
    }

    /**
     * Render a view without layout
     */
    public static function renderPlain(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "View file not found: {$view}";
            return;
        }

        include $viewPath;
    }

    /**
     * Render a partial (included view)
     */
    public static function partial(string $partial, array $data = []): string
    {
        extract($data);
        $partialPath = __DIR__ . '/../../views/partials/' . $partial . '.php';

        if (!file_exists($partialPath)) {
            return '';
        }

        ob_start();
        include $partialPath;
        return ob_get_clean();
    }
}
