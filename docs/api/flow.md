# Flow Class

The `Flow` class provides elegant, chainable route definitions.

## HTTP Methods

```php
Flow::GET()->do(callable $handler);
Flow::POST()->do(callable $handler);
Flow::PUT()->do(callable $handler);
Flow::PATCH()->do(callable $handler);
Flow::DELETE()->do(callable $handler);
Flow::ANY()->do(callable $handler);
```

## Route Parameters

Use `{param}` in patterns for dynamic segments:

```php
// Required parameter
Flow::GET('/users/{id}')->do(function($req) {
    $id = $req->param('id');
    return Response::json(['user_id' => $id]);
});

// Optional parameter
Flow::GET('/users/{id?}')->do(fn($req) => {
    $id = $req->param('id', 'default');
    return Response::json(['user_id' => $id]);
});

// Nested parameters
Flow::GET('/posts/{postId}/comments/{commentId}')->do(fn($req) => {
    $postId = $req->param('postId');
    $commentId = $req->param('commentId');
    return Response::json(['post' => $postId, 'comment' => $commentId]);
});
```

## File-based Routing Parameters

In file-based routes, use `[param]` syntax in your filenames:

```
app/router/users/[id].php                    # GET /users/123
app/router/posts/[postId]/comments.php       # GET /posts/123/comments
app/router/posts/[postId]/comments/[commentId].php  # GET /posts/123/comments/456
```

These are automatically converted to `{param}` patterns. Access parameters via `$req->param('param')`:

```php
// app/router/users/[id].php
Flow::GET()->do(function($req) {
    $userId = $req->param('id');
    return Response::json(['user' => $userId]);
});
```

## Controller Binding

```php
Flow::GET()->to(HomeController::class, 'index');
Flow::POST()->to(UserController::class, 'store');
```

## Middleware

```php
Flow::use(callable $middleware);
```

## Route Groups

```php
Flow::group('/admin', ['auth'], function() {
    // /admin/dashboard
    Flow::GET('/dashboard')->do(fn($req) => Response::view('admin/dashboard'));
    
    // /admin/users
    Flow::GET('/users')->to(AdminController::class, 'listUsers');
    
    // /admin/settings
    Flow::POST('/settings')->to(AdminController::class, 'updateSettings');
});
```

## Named Routes

```php
Flow::GET()->name('home')->do(fn($req) => Response::view('home'));

// Generate URL
$url = Flow::route('home');  // Returns base URL + route pattern
```

## Examples

### Simple Routes

```php
Flow::GET()->do(fn($req) => 'Hello World');
Flow::POST()->do(fn($req) => Response::json($req->all()));
```

### API Routes

```php
Flow::GET('/api/users')->do(fn($req) => Response::json([
    ['id' => 1, 'name' => 'John'],
    ['id' => 2, 'name' => 'Jane']
]));

Flow::POST('/api/users')->do(fn($req) => {
    $data = $req->only(['name', 'email']);
    return Response::success($data, 'User created', 201);
});
```

### Parameter Validation

```php
Flow::GET('/users/{id}')->do(function($req) {
    $id = $req->param('id');
    
    if (!is_numeric($id)) {
        return Response::error('Invalid user ID', 400);
    }
    
    return Response::json(['user_id' => (int) $id]);
});
```

### Route Groups with Prefix

```php
Flow::group('/api/v1', ['cors'], function() {
    Flow::GET('/users')->to(UserController::class, 'index');
    Flow::POST('/users')->to(UserController::class, 'store');
    Flow::GET('/users/{id}')->to(UserController::class, 'show');
    Flow::PUT('/users/{id}')->to(UserController::class, 'update');
    Flow::DELETE('/users/{id}')->to(UserController::class, 'destroy');
});
```

### Optional Parameters with Defaults

```php
Flow::GET('/search')->do(fn($req) => {
    $query = $req->input('q', '');
    $page = $req->input('page', 1);
    $limit = $req->input('limit', 10);
    
    return Response::json([
        'query' => $query,
        'page' => (int) $page,
        'limit' => (int) $limit
    ]);
});
```

## Complete Route File Example

```php
<?php
// app/router/api/users/[id].php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function($req) {
    $userId = $req->param('id');
    
    // Fetch user from database...
    $user = ['id' => $userId, 'name' => 'User ' . $userId];
    
    return Response::success($user);
});

Flow::PUT()->do(function($req) {
    $userId = $req->param('id');
    $data = $req->only(['name', 'email']);
    
    // Update user...
    
    return Response::success(null, 'User updated');
});

Flow::DELETE()->do(function($req) {
    $userId = $req->param('id');
    
    // Delete user...
    
    return Response::success(null, 'User deleted');
});
```

## Notes

- Route parameters from `[param]` in file names are automatically available via `$req->param('param')`
- Optional parameters `{param?}` in patterns will return `null` if not provided
- Use `$req->input()` for query string or POST data, `$req->param()` for route parameters
- Route groups can be nested for complex routing structures