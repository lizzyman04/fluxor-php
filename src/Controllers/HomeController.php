<?php

namespace App\Controllers;

use Fluxor\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home', [
            'title' => 'Welcome',
            'message' => 'Hello from Controller!'
        ]);
    }
}