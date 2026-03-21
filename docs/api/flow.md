# Flow Class

The `Flow` class provides elegant, chainable route definitions for file-based routing.

## HTTP Methods

```php
Flow::GET()->do(callable $handler);
Flow::POST()->do(callable $handler);
Flow::PUT()->do(callable $handler);
Flow::PATCH()->do(callable $handler);
Flow::DELETE()->do(callable $handler);
Flow::ANY()->do(callable $handler);
```

## File-based Routing

In Fluxor, routes are defined by your folder structure. Each PHP file in `app/router/` becomes a route.

### Basic Routes

```
app/router/index.php     # GET /
app/router/about.php     # GET /about
app/router/contact/index.php  # GET /contact
```

### Dynamic Routes

Use `[param]` syntax in your filenames:

```
app/router/users/[id].php                    # GET /users/123
app/router/posts/[postId]/comments.php       # GET /posts/123/comments
app/router/posts/[postId]/comments/[commentId].php  # GET /posts/123/comments/456
```

Access the parameter:

```php
// app/router/users/[id].php
Flow::GET()->do(function($req) {
    $userId = $req->param('id');
    return Response::json(['user' => $userId]);
});
```

### Nested Dynamic Routes

```
app/router/posts/[category]/[id].php
```

### Optional Groups (Prefixes)

Use `(group)` for logical grouping without affecting URLs:

```
app/router/(admin)/dashboard.php   # URL: /dashboard
app/router/(admin)/users.php        # URL: /users
```

The group name is ignored in the URL path.

## Controller Binding

```php
Flow::GET()->to(HomeController::class, 'index');
Flow::POST()->to(UserController::class, 'store');
```

## Middleware

```php
Flow::use(callable $middleware);
```

## Named Routes

```php
Flow::GET()->name('home')->do(fn($req) => Response::view('home'));

// Generate URL
$url = Flow::route('home');
```

## Examples

### Simple Routes

```php
// app/router/index.php
Flow::GET()->do(fn($req) => 'Hello World');

// app/router/api/users.php
Flow::POST()->do(fn($req) => Response::json($req->all()));
```

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

- Route parameters from `[param]` in file names are automatically available via `$req->param('param')`
- Use `$req->input()` for query string or POST data, `$req->param()` for route parameters
- For grouping related routes, use subdirectories - it's simpler and follows the file-based routing philosophy
- Middleware can be applied globally with `Flow::use()` or per-route using the Router