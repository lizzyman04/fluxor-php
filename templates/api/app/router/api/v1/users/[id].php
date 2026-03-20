<?php
use Fluxor\Flow;
use Fluxor\Response;

$users = [
    1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
    2 => ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    3 => ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com'],
];

Flow::GET()->do(function($req) use ($users) {
    $id = (int) $req->param('id');
    
    if (!isset($users[$id])) {
        return Response::error('User not found', 404);
    }
    
    return Response::success($users[$id]);
});

Flow::PUT()->do(function($req) use (&$users) {
    $id = (int) $req->param('id');
    $data = $req->only(['name', 'email']);
    
    if (!isset($users[$id])) {
        return Response::error('User not found', 404);
    }
    
    $users[$id] = array_merge($users[$id], $data);
    return Response::success($users[$id], 'User updated');
});

Flow::DELETE()->do(function($req) use (&$users) {
    $id = (int) $req->param('id');
    
    if (!isset($users[$id])) {
        return Response::error('User not found', 404);
    }
    
    unset($users[$id]);
    return Response::success(null, 'User deleted');
});