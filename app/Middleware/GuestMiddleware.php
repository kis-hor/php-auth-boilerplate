<?php

namespace App\Middleware;

use App\Core\Auth;

class GuestMiddleware
{
    public function handle(): bool
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        return true;
    }
}
