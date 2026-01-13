<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Debug;

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->view('auth/login');
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        Debug::logInfo("Login attempt", [
            'username' => $username,
            'password_length' => strlen($password),
            'post_data' => $_POST
        ]);

        if (Auth::login($username, $password)) {
            Debug::logSuccess("Login successful for: {$username}");
            $this->redirect('/');
        }

        Debug::logError("Login failed for: {$username}");
        $this->view('auth/login', [
            'error' => 'Invalid credentials or account locked'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
