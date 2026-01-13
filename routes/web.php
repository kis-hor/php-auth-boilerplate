<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;
use App\Middleware\AuthMiddleware;



/** @var Router $router */


$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/', [HomeController::class, 'index'])->middleware([AuthMiddleware::class]);
