<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    $userId = $req->param('id');
    
    return Response::success([
        'id' => $userId,
        'name' => 'User ' . $userId,
        'email' => "user{$userId}@example.com"
    ]);
});