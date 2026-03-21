# File-based Routing

Fluxor uses a **file-based routing system** inspired by Next.js. Routes are defined by your folder structure.

## Basic Routes

Create PHP files in `app/router/`:

| File | URL |
|------|-----|
| `app/router/index.php` | `/` |
| `app/router/about.php` | `/about` |
| `app/router/contact/index.php` | `/contact` |

## Dynamic Routes

Use `[param]` syntax for dynamic segments:

```
app/router/posts/[slug].php
```

Access the parameter:

```php
<?php
// app/router/posts/[slug].php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function($req) {
    $slug = $req->param('slug');
    return Response::json(['slug' => $slug]);
});
```

## Nested Dynamic Routes

```
app/router/posts/[category]/[id].php
```

## Optional Groups (Prefixes)

Use `(group)` for logical grouping without affecting URLs:

```
app/router/(admin)/dashboard.php   # URL: /dashboard
app/router/(admin)/users.php        # URL: /users
app/router/(api)/v1/users.php       # URL: /v1/users
```

The group name is ignored in the URL path.

## API Routes

Create API endpoints in `app/router/api/`:

```
app/router/api/users.php           # GET /api/users
app/router/api/users/[id].php      # GET /api/users/{id}
```

## Grouping Routes

To group related routes, use subdirectories:

```
app/router/
├── admin/
│   ├── dashboard.php      # /admin/dashboard
│   ├── users.php          # /admin/users
│   └── settings.php       # /admin/settings
└── api/
    └── v1/
        ├── users.php      # /api/v1/users
        └── posts.php      # /api/v1/posts
```

This is the recommended way to organize routes in Fluxor.

## Complete Example: User API

```
app/router/
└── api/
    └── v1/
        └── users/
            ├── index.php      # GET /api/v1/users
            ├── store.php      # POST /api/v1/users
            └── [id].php       # GET /api/v1/users/{id}
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