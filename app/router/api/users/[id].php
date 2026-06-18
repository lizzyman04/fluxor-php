<?php

use Fluxor\Core\Routing\Flow;
use Fluxor\Core\Http\Response;

Flow::GET()->do(function ($req) {
    $id = $req->param('id');
    return Response::json([
        'id' => (int) $id,
        'name' => "User {$id}",
        'email' => "user{$id}@example.com",
        'profile_url' => base_url("api/users/{$id}/profile")
    ]);
});