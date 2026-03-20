<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::json([
        'name' => 'Fluxor API',
        'version' => '1.0.0',
        'status' => 'active',
        'endpoints' => [
            'users' => '/api/v1/users',
            'users/:id' => '/api/v1/users/{id}',
            'posts' => '/api/v1/posts',
            'posts/:id' => '/api/v1/posts/{id}',
        ]
    ]);
});