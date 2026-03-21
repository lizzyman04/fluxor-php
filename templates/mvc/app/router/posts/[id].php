<?php
use Fluxor\Flow;
use Fluxor\Response;
use App\Controllers\PostController;

Flow::GET()->to(PostController::class, 'show');