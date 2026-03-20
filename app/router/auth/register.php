<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::POST()->do(function ($req) {
    $data = $req->only(['name', 'email', 'password']);
    
    // Validation
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        return Response::error('All fields are required', 422);
    }
    
    // Demo registration (would save to database in real app)
    return Response::success([
        'id' => rand(1000, 9999),
        'name' => $data['name'],
        'email' => $data['email']
    ], 'User registered successfully');
});