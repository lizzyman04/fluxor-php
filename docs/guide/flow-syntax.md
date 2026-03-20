# Flow Syntax

The `Flow` class provides an elegant, chainable syntax for defining routes.

## Basic Usage

```php
<?php
use Fluxor\Flow;
use Fluxor\Response;

// Simple GET route
Flow::GET()->do(function($req) {
    return Response::text('Hello World');
});

// POST route
Flow::POST()->do(function($req) {
    $data = $req->all();
    return Response::success($data);
});
```

## HTTP Methods

```php
Flow::GET()->do(fn($req) => ...);
Flow::POST()->do(fn($req) => ...);
Flow::PUT()->do(fn($req) => ...);
Flow::PATCH()->do(fn($req) => ...);
Flow::DELETE()->do(fn($req) => ...);
Flow::ANY()->do(fn($req) => ...);  // Any method
```

## Controller Binding

```php
Flow::GET()->to(HomeController::class, 'index');
Flow::POST()->to(UserController::class, 'store');
```

## Middleware

```php
Flow::use(function($req) {
    if (!$req->isAuthenticated()) {
        return Response::redirect('/login');
    }
});
```

## Named Routes

```php
Flow::GET()->name('home')->do(fn($req) => ...);

// Generate URL
$url = Flow::route('home');
```

## Route Groups

```php
Flow::group('/admin', ['auth'], function() {
    Flow::GET()->do(fn($req) => Response::view('admin/dashboard'));
    Flow::POST()->to(AdminController::class, 'update');
});