<?php

namespace App\Controllers;

use Fluxor\Controller;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        
        return $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'posts' => $posts
        ]);
    }
}