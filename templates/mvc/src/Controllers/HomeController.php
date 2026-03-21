<?php

namespace App\Controllers;

use Fluxor\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home/index', [
            'title' => 'Welcome to Fluxor',
            'message' => 'A modern PHP framework with file-based routing'
        ]);
    }
    
    public function about()
    {
        return $this->view('home/about', [
            'title' => 'About Fluxor'
        ]);
    }
    
    public function contact()
    {
        return $this->view('home/contact', [
            'title' => 'Contact Us'
        ]);
    }
    
    public function sendContact()
    {
        $name = $this->request->input('name');
        $email = $this->request->input('email');
        $message = $this->request->input('message');
        
        // Process contact form (send email, save to database, etc.)
        
        $this->request->session('flash', 'Message sent successfully!');
        
        return $this->redirect('/contact');
    }
}