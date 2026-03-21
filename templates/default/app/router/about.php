<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::view('about', [
        'title' => 'About Fluxor'
    ]);
});