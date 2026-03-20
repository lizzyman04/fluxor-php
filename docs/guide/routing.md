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

## Route Examples

Your current project includes working examples:

- `app/router/index.php` - Home page
- `app/router/api/hello/index.php` - API example
- `app/router/auth/login.php` - Authentication routes
- `app/router/posts/[id]/comments.php` - Nested dynamic routes