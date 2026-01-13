<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public static function findByUsername(string $username): ?array
    {
        $instance = new self();

        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $result = $instance->query($sql, ['username' => $username]);

        error_log("Finding user: {$username}");
        error_log("Query result: " . json_encode($result));

        if (!$result || empty($result)) {
            error_log("User not found in database");
            return null;
        }

        $user = $result[0];
        error_log("User data: id={$user['id']}, status={$user['status']}, hash_start=" . substr($user['password_hash'], 0, 20));

        return $user;
    }

    public static function updateLastLogin(int $userId): void
    {
        $instance = new self();
        $instance->execute(
            "UPDATE users SET last_login_at = NOW(), login_attempts = 0 WHERE id = :id",
            ['id' => $userId]
        );
    }

    public static function incrementLoginAttempts(int $userId): void
    {
        $instance = new self();
        $instance->execute(
            "UPDATE users SET login_attempts = login_attempts + 1 WHERE id = :id",
            ['id' => $userId]
        );
    }
}
