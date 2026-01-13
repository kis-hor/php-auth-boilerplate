<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function login(string $username, string $password): bool
    {
        $user = User::findByUsername($username);

        if (!$user) {
            error_log("User not found: {$username}");
            return false;
        }

        if ($user['status'] !== 'active') {
            error_log("User not active: {$username} (status: {$user['status']})");
            return false;
        }

        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            error_log("User locked: {$username}");
            return false;
        }

        $passwordMatch = password_verify($password, $user['password_hash']);
        error_log("Password verification: " . ($passwordMatch ? 'PASS' : 'FAIL'));
        error_log("Hash from DB: " . substr($user['password_hash'], 0, 20) . "...");

        if (!$passwordMatch) {
            error_log("Password mismatch for user: {$username}");
            User::incrementLoginAttempts($user['id']);
            return false;
        }

        Session::set('user', [
            'id'   => $user['id'],
            'name' => $user['username'],
            'role' => $user['role']
        ]);

        User::updateLastLogin($user['id']);
        error_log("Login successful for: {$username}");

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
