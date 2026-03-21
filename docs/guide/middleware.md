# Middleware

Middleware filters HTTP requests before they reach your routes.

## Creating Middleware

Middleware is a callable that receives the request:

```php
$authMiddleware = function($request) {
    if (!$request->isAuthenticated()) {
        return Fluxor\Response::redirect('/login');
    }
    // Return null to continue
    return null;
};
```

## Registering Middleware

### Global Middleware

```php
use Fluxor\Flow;

Flow::use(function($request) {
    // Log all requests
    error_log($request->method . ' ' . $request->path);
    return null;
});
```

### Route-Specific Middleware

For route-specific middleware, you can use the `use()` method before defining the route:

```php
use Fluxor\Flow;

$auth = fn($req) => $req->isAuthenticated() ? null : Response::redirect('/login');

// Apply middleware to a specific route
Flow::use($auth);
Flow::GET('/admin/dashboard')->do(fn($req) => Response::view('admin/dashboard'));
```

### Router Middleware

```php
$app = new Fluxor\App();
$app->getRouter()->addMiddleware('cors', function($req) {
    header('Access-Control-Allow-Origin: *');
    if ($req->method === 'OPTIONS') {
        exit(0);
    }
});
```

## Middleware Response

Middleware can return:

- **`null`**: Continue to next middleware/route
- **`Response`**: Stop and return this response
- **`false`**: Return a 403 Forbidden error

## Example: Throttle Middleware

```php
$throttle = function($request) {
    $ip = $request->getClientIp();
    $key = "throttle:$ip";
    $attempts = $_SESSION[$key] ?? 0;

    if ($attempts > 60) {
        return Fluxor\Response::error('Too many requests', 429);
    }

    $_SESSION[$key] = $attempts + 1;
    return null;
};

Flow::use($throttle);
```

## Middleware Order

Middleware is executed in the order it is signuped:

```php
// Executed first
Flow::use(fn($req) => error_log('First'));

// Executed second
Flow::use(fn($req) => error_log('Second'));

// Executed third (if no middleware returns a response)
Flow::GET()->do(fn($req) => Response::text('Hello'));
```

## Notes

- Middleware can be used for authentication, logging, CORS, rate limiting, etc.
- Use `Flow::use()` for global middleware that applies to all routes
- For route-specific middleware, use `Flow::use()` just before defining the route
- Middleware that returns a `Response` stops the execution chain