# Flow Class

The `Flow` class provides elegant, chainable route definitions.

## Methods

### HTTP Methods

```php
Flow::GET()->do(callable $handler);
Flow::POST()->do(callable $handler);
Flow::PUT()->do(callable $handler);
Flow::PATCH()->do(callable $handler);
Flow::DELETE()->do(callable $handler);
Flow::ANY()->do(callable $handler);
```

### Controller Binding

```php
Flow::GET()->to(ControllerClass::class, 'method');
Flow::POST()->to(UserController::class, 'store');
```

### Middleware

```php
Flow::use(callable $middleware);
```

### Route Groups

```php
Flow::group($prefix, array $middleware, callable $callback);
```

### Named Routes

```php
Flow::GET()->name('home')->do(fn($req) => ...);
Flow::route('home');  // Generate URL
```

### Execution

```php
return Flow::execute($request);
```

## Examples

### Simple Routes

```php
Flow::GET()->do(fn($req) => 'Hello World');
Flow::POST()->do(fn($req) => Response::json($req->all()));
```

### With Parameters

```php
Flow::GET('/users/{id}')->do(function($req) {
    $id = $req->param('id');
    return Response::json(['user_id' => $id]);
});
```

### Controller Routes

```php
Flow::GET()->to(HomeController::class, 'index');
Flow::POST('/users')->to(UserController::class, 'store');
```

### Route Groups

```php
Flow::group('/admin', ['auth'], function() {
    Flow::GET()->do(fn($req) => Response::view('admin/dashboard'));
    Flow::GET('/users')->to(AdminController::class, 'listUsers');
});
```