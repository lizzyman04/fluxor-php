<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    return Response::html('
        <!DOCTYPE html>
        <html>
        <head>
            <title>Fluxor</title>
            <style>
                body { font-family: sans-serif; text-align: center; padding: 50px; }
                h1 { color: #4f46e5; }
            </style>
        </head>
        <body>
            <h1>🚀 Fluxor is running!</h1>
            <p>Your minimalist PHP framework is ready.</p>
            <p><a href="/api/hello?name=Fluxor">Try the API →</a></p>
        </body>
        </html>
    ');
});