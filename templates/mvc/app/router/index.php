<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Controllers\HomeController;

Flow::GET()->to(HomeController::class, 'index');