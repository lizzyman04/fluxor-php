<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Middleware\Auth;
use App\Controllers\DashboardController;

// Apply auth middleware
Flow::use(function($req) {
    return Auth::check($req);
});

Flow::GET()->to(DashboardController::class, 'index');