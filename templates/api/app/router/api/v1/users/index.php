<?php
use Fluxor\Flow;
use Fluxor\Response;

$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com'],
];

Flow::GET()->do(function($req) use ($users) {
    return Response::success($users);
});

Flow::POST()->do(function($req) use (&$users) {
    $data = $req->only(['name', 'email']);
    
    if (empty($data['name']) || empty($data['email'])) {
        return Response::error('Name and email are required', 422);
    }
    
    $newId = count($users) + 1;
    $newUser = ['id' => $newId, ...$data];
    $users[] = $newUser;
    
    return Response::success($newUser, 'User created', 201);
});