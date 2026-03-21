<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Middleware\Auth;
use App\Controllers\PostController;

Flow::use(function($req) {
    return Auth::check($req);
});

Flow::GET()->to(PostController::class, 'create');
Flow::POST()->to(PostController::class, 'store');