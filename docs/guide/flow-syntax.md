# Flow Syntax

The `Flow` class provides an elegant, chainable syntax for defining routes in file-based routing.

## Basic Usage

```php
<?php
// app/router/index.php
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

## File-based Parameters

In file-based routes, use `[param]` syntax in your filenames:

```
app/router/users/[id].php
```

Access the parameter:

```php
<?php
// app/router/users/[id].php
Flow::GET()->do(function($req) {
    $id = $req->param('id');
    return Response::json(['user' => $id]);
});
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

## Examples

### RESTful API with File-based Routing

Create the following structure:

```
app/router/
└── api/
    └── v1/
        └── users/
            ├── index.php      # GET /api/v1/users
            ├── store.php      # POST /api/v1/users
            └── [id].php       # GET/PUT/DELETE /api/v1/users/{id}
```

**`app/router/api/v1/users/index.php`**
```php
<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(fn($req) => Response::json([
    ['id' => 1, 'name' => 'John'],
    ['id' => 2, 'name' => 'Jane']
]));
```

**`app/router/api/v1/users/store.php`**
```php
<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::POST()->do(fn($req) => {
    $data = $req->only(['name', 'email']);
    return Response::success($data, 'User created', 201);
});
```

**`app/router/api/v1/users/[id].php`**
```php
<?php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function($req) {
    $id = $req->param('id');
    return Response::json(['id' => $id, 'name' => 'User ' . $id]);
});

Flow::PUT()->do(function($req) {
    $id = $req->param('id');
    $data = $req->only(['name', 'email']);
    return Response::success(null, "User {$id} updated");
});

Flow::DELETE()->do(function($req) {
    $id = $req->param('id');
    return Response::success(null, "User {$id} deleted");
});
```

## Notes

- The `Flow` class uses magic methods to support HTTP verbs as static calls
- Use `$req->param()` for route parameters, `$req->input()` for request data
- For grouping routes, use subdirectories - it's simpler and follows the file-based routing philosophy
- Middleware can be applied globally with `Flow::use()` or per-route using the Router