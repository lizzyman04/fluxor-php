<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Controllers\AuthController;

Flow::GET()->to(AuthController::class, 'showRegister');
Flow::POST()->to(AuthController::class, 'register');