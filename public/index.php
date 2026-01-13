<?php

declare(strict_types=1);

session_start();

// Autoload (Composer optional but recommended)
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Router;

// Bootstrap app
$app = new App();
$router = new Router();

// Register routes
$app->registerRoutes($router);

// Dispatch request
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
