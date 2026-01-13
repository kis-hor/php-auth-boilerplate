<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;



/** @var Router $router */


// Auth routes
$router->get('/login', [AuthController::class, 'showLogin'])->middleware([GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'login'])->middleware([GuestMiddleware::class]);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/', [HomeController::class, 'index'])->middleware([AuthMiddleware::class]);

// User management (Admin only)
$router->get('/users', [UserController::class, 'index'])->middleware([AuthMiddleware::class]);
$router->get('/users/create', [UserController::class, 'create'])->middleware([AuthMiddleware::class]);
$router->post('/users/store', [UserController::class, 'store'])->middleware([AuthMiddleware::class]);
$router->get('/users/edit/{id}', [UserController::class, 'edit'])->middleware([AuthMiddleware::class]);
$router->post('/users/update/{id}', [UserController::class, 'update'])->middleware([AuthMiddleware::class]);
$router->get('/users/delete/{id}', [UserController::class, 'delete'])->middleware([AuthMiddleware::class]);
