<?php

namespace App\Controllers;

use Fluxor\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('auth/login');
    }

    public function login()
    {
        $data = $this->request->only(['email', 'password']);

        if ($data['email'] === 'admin@example.com' && $data['password'] === 'password') {
            $_SESSION['user'] = $data;
            return $this->success(null, 'Logged in');
        }

        return $this->error('Invalid credentials', 401);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        return $this->redirect('/');
    }
}