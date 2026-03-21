<?php

namespace App\Controllers;

use Fluxor\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if ($this->request->isAuthenticated()) {
            return $this->redirect('/dashboard');
        }
        
        return $this->view('auth/login', [
            'title' => 'Login'
        ]);
    }
    
    public function login()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        
        if (empty($email) || empty($password)) {
            return $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Email and password are required'
            ]);
        }
        
        $user = User::findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid credentials'
            ]);
        }
        
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        return $this->redirect('/dashboard');
    }
    
    public function showRegister()
    {
        if ($this->request->isAuthenticated()) {
            return $this->redirect('/dashboard');
        }
        
        return $this->view('auth/register', [
            'title' => 'Register'
        ]);
    }
    
    public function register()
    {
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
            $errors['email'] = 'Email already registered';
        }
        
        if (!empty($errors)) {
            return $this->view('auth/register', [
                'title' => 'Register',
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email]
            ]);
        }
        
        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user'
        ]);
        
        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user'
        ];
        
        return $this->redirect('/dashboard');
    }
    
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        
        return $this->redirect('/');
    }
}