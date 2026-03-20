<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::json([
        'version' => '1.0.0',
        'status' => 'active'
    ]);
});