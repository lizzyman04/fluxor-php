<?php

namespace App\Controllers;

use Core\Auth;
use App\Models\User;
use Fluxor\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirect('/');
        }

        return $this->view('auth/login', [
            'title' => 'Login',
            'csrf_token' => Auth::csrfToken(),
            'redirect' => $this->request->input('redirect', '/')
        ]);
    }

    public function login()
    {
        if (!Auth::validateCsrf($this->request->input('csrf_token'))) {
            return $this->error('Invalid CSRF token', 419);
        }

        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $remember = (bool) $this->request->input('remember');
        $redirect = $this->request->input('redirect', '/');

        if (empty($email) || empty($password)) {
            return $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Email and password are required',
                'csrf_token' => Auth::csrfToken(),
                'redirect' => $redirect
            ]);
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid credentials',
                'csrf_token' => Auth::csrfToken(),
                'redirect' => $redirect
            ]);
        }

        Auth::login([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ], $remember);

        return $this->redirect($redirect);
    }

    public function showSignup()
    {
        if (Auth::check()) {
            return $this->redirect('/');
        }

        return $this->view('auth/signup', [
            'title' => 'Signup',
            'csrf_token' => Auth::csrfToken()
        ]);
    }

    public function signup()
    {
        if (!Auth::validateCsrf($this->request->input('csrf_token'))) {
            return $this->error('Invalid CSRF token', 419);
        }

        $name = $this->request->input('name');
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $passwordConfirm = $this->request->input('password_confirm');

        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Name is required';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        if (User::findByEmail($email)) {
            $errors['email'] = 'Email already signuped';
        }

        if (!empty($errors)) {
            return $this->view('auth/signup', [
                'title' => 'Signup',
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email],
                'csrf_token' => Auth::csrfToken()
            ]);
        }

        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        Auth::login([
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user'
        ]);

        return $this->redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return $this->redirect('/');
    }
}