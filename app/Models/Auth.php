<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function login(string $username, string $password): bool
    {
        $user = User::findByUsername($username);

        // Debug
        error_log("User found: " . json_encode($user));
        error_log("Password verify: " . (password_verify($password, $user['password_hash'] ?? '') ? 'true' : 'false'));

        if (!$user || $user['status'] !== 'active') {
            error_log("User not found or not active");
            return false;
        }

        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            error_log("User locked");
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            error_log("Password mismatch");
            User::incrementLoginAttempts($user['id']);
            return false;
        }

        Session::set('user', [
            'id'   => $user['id'],
            'name' => $user['username'],
            'role' => $user['role']
        ]);

        User::updateLastLogin($user['id']);

        return true;
    }

    public static function logout(): void
    {
        Session::forget('user');
        Session::destroy();
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function hasRole(array $roles): bool
    {
        return self::check() && in_array(self::user()['role'], $roles);
    }
}
