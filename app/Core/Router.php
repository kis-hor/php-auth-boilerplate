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

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 | Route not found";
            return;
        }

        // Check middleware
        if (isset($this->middleware[$method][$uri])) {
            foreach ($this->middleware[$method][$uri] as $middlewareClass) {
                if (is_string($middlewareClass)) {
                    $middleware = new $middlewareClass();
                    $response = $middleware->handle();
                    if ($response === false) {
                        return;
                    }
                }
            }
        }

        $action = $this->routes[$method][$uri];

        // Controller@method format
        if (is_array($action)) {
            [$controller, $methodName] = $action;
            $controllerInstance = new $controller();
            $controllerInstance->$methodName();
            return;
        }

        // Closure
        call_user_func($action);
    }

    private function normalize(string $uri): string
    {
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }
}
