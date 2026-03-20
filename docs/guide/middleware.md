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

```php
use Fluxor\Flow;

$auth = fn($req) => $req->isAuthenticated() ? null : Response::redirect('/login');

Flow::group('/admin', [$auth], function() {
    Flow::GET()->do(fn($req) => Response::view('admin/dashboard'));
    Flow::POST()->to(AdminController::class, 'update');
});
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
```