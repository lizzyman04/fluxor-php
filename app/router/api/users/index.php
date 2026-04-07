<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::json([
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Smith'],
        ['id' => 3, 'name' => 'Bob Johnson']
    ]);
});