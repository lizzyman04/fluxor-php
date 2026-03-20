<?php

use Fluxor\Flow;
use Fluxor\Response;

// Show login form (GET)
Flow::GET()->do(function ($req) {
    return Response::view('auth/login');
});

// Process login (POST)
Flow::POST()->do(function ($req) {
    $email = $req->input('email');
    $password = $req->input('password');
    
    if (empty($email) || empty($password)) {
        return Response::error('Email and password required', 422);
    }
    
    // Simple authentication (demo only!)
    if ($email === 'admin@example.com' && $password === 'password') {
        $_SESSION['user'] = ['email' => $email, 'name' => 'Admin'];
        return Response::success(null, 'Logged in successfully');
    }
    
    return Response::error('Invalid credentials', 401);
});