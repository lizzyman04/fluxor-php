<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::html('<h1>Admin Dashboard</h1><p>Welcome to the admin panel.</p>');
});