<?php

namespace App\Middleware;

use Fluxor\Core\Http\Response;

class Auth
{
    public static function check($request)
    {
        if (!$request->isAuthenticated()) {
            return Response::redirect('/auth/login');
        }
        
        return null;
    }
}