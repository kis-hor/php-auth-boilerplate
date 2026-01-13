<?php

namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        return true;
    }
}
