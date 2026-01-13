<?php

namespace App\Middleware;

use App\Core\Auth;

class RoleMiddleware
{
    public static function handle(array $roles): void
    {
        if (!Auth::hasRole($roles)) {
            http_response_code(403);
            echo "403 | Unauthorized";
            exit;
        }
    }
}
