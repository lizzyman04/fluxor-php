<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::view('contact', [
        'title' => 'Contact Us'
    ]);
});

Flow::POST()->do(function ($req) {
    $name = $req->input('name');
    $email = $req->input('email');
    $message = $req->input('message');

    // Process contact form...

    return Response::success(null, 'Message sent successfully!');
});