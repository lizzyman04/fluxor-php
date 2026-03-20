<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    $name = $req->input('name', 'World');
    return Response::json([
        'message' => "Hello, {$name}!",
        'time' => time()
    ]);
});