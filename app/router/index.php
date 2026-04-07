<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function($req) {
    return Response::view('home', [
        'title' => 'Welcome to Fluxor',
        'message' => 'Your lightweight PHP application is ready!'
    ]);
});