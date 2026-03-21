<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Controllers\HomeController;

Flow::GET()->to(HomeController::class, 'contact');
Flow::POST()->to(HomeController::class, 'sendContact');