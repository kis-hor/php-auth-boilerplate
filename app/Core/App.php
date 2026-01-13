<?php

namespace App\Core;


class App
{
    public function registerRoutes(Router $router): void
    {
        require __DIR__ . '/../../routes/web.php';
    }
}
