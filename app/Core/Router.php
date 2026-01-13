<?php

namespace App\Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    private array $middleware = [
        'GET' => [],
        'POST' => []
    ];

    private ?string $currentRoute = null;
    private ?string $currentMethod = null;

    public function get(string $uri, callable|array $action): self
    {
        $uri = $this->normalize($uri);
        $this->routes['GET'][$uri] = $action;
        $this->currentRoute = $uri;
        $this->currentMethod = 'GET';
        return $this;
    }

    public function post(string $uri, callable|array $action): self
    {
        $uri = $this->normalize($uri);
        $this->routes['POST'][$uri] = $action;
        $this->currentRoute = $uri;
        $this->currentMethod = 'POST';
        return $this;
    }

    public function middleware(array $middlewareClasses): self
    {
        if ($this->currentRoute && $this->currentMethod) {
            $this->middleware[$this->currentMethod][$this->currentRoute] = $middlewareClasses;
        }
        return $this;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalize($uri);

        // Try exact match first
        if (isset($this->routes[$method][$uri])) {
            $this->executeRoute($method, $uri, $this->routes[$method][$uri]);
            return;
        }

        // Try pattern matching for dynamic routes
        foreach ($this->routes[$method] as $pattern => $action) {
            if ($this->matchesPattern($pattern, $uri, $params)) {
                $this->executeRoute($method, $pattern, $action, $params);
                return;
            }
        }

        http_response_code(404);
        $renderer = new ViewRenderer();
        echo $renderer->render('notfound', ['requestedPath' => $uri], 'main');
    }

    private function executeRoute(string $method, string $pattern, $action, array $params = []): void
    {
        // Check middleware
        if (isset($this->middleware[$method][$pattern])) {
            foreach ($this->middleware[$method][$pattern] as $middlewareClass) {
                if (is_string($middlewareClass)) {
                    $middleware = new $middlewareClass();
                    $response = $middleware->handle();
                    if ($response === false) {
                        return;
                    }
                }
            }
        }

        // Controller@method format
        if (is_array($action)) {
            [$controller, $methodName] = $action;
            $controllerInstance = new $controller();

            if (!empty($params)) {
                $controllerInstance->$methodName(...array_values($params));
            } else {
                $controllerInstance->$methodName();
            }
            return;
        }

        // Closure
        call_user_func($action, ...$params);
    }

    private function matchesPattern(string $pattern, string $uri, &$params = []): bool
    {
        // Convert pattern like /users/edit/{id} to regex
        $regex = preg_replace_callback('/{(\w+)}/', function ($matches) {
            return '(?P<' . $matches[1] . '>\d+)';
        }, $pattern);

        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            // Extract named parameters
            $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    private function normalize(string $uri): string
    {
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }
}
