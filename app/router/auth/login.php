<?php
use Fluxor\Flow;
use App\Controllers\AuthController;

Flow::GET()->to(AuthController::class, 'showLogin');
Flow::POST()->to(AuthController::class, 'login');