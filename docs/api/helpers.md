# Helper Functions

Fluxor provides a set of global helper functions to simplify common tasks.

## Application Helpers

### `app(?string $service = null)`

Returns the application instance or a registered service.

```php
// Get application instance
$app = app();

// Get a registered service
$view = app('view');
$router = app('router');
$config = app('config');
```

### `base_path(string $path = ''): string`

Returns the absolute path to the project root.

```php
$root = base_path();
$configPath = base_path('config/app.php');
$storagePath = base_path('storage/logs');
```

### `base_url(string $path = ''): string`

Returns the base URL of the application.

```php
$home = base_url();
$apiUrl = base_url('api/users');
$assetUrl = base_url('assets/css/app.css');
```

### `asset(string $path): string`

Generates a URL for an asset file in the `public/assets/` directory.

```php
$css = asset('css/app.css');
$js = asset('js/app.js');
$img = asset('images/logo.png');
```

### `config(string $key, $default = null)`

Retrieves a configuration value.

```php
$appName = config('app_name', 'Fluxor');
$debug = config('debug', false);
$timezone = config('timezone', 'UTC');
```

### `env(string $key, $default = null)`

Retrieves an environment variable value.

```php
$appEnv = env('APP_ENV', 'production');
$dbName = env('DB_NAME', 'database');
$apiKey = env('API_KEY');
```

## HTTP Helpers

### `http_status_message(int $code): string`

Returns the standard message for an HTTP status code.

```php
echo http_status_message(200);  // "OK"
echo http_status_message(404);  // "Not Found"
echo http_status_message(500);  // "Internal Server Error"
```

### `abort(int $code, ?string $message = null): void`

Throws an HTTP exception with the given status code.

```php
abort(404);                    // Throws 404 exception
abort(403, 'Access denied');   // Throws 403 with custom message
abort(500);                    // Throws 500 exception
```

### `redirect(string $url, int $status = HttpStatusCode::FOUND): Response`

Returns a redirect response.

```php
return redirect('/dashboard');
return redirect('/login', HttpStatusCode::TEMPORARY_REDIRECT);
return redirect('https://example.com');
```

## Debug Helpers

### `dd(...$vars): void`

Dumps variables and dies (halts execution).

```php
dd($user, $posts);           // Dumps both variables and stops execution
dd($request->all());          // Dumps all request data
dd($result);                  // Dumps and stops
```

### `dump(...$vars): void`

Dumps variables without halting execution.

```php
dump($user);                  // Prints user data, continues execution
dump($request->headers);      // Prints headers
dump($data, $meta);           // Prints multiple variables
```

## Complete Example

```php
<?php
// app/router/users/[id].php

use Fluxor\Flow;
use Fluxor\Response;
use Fluxor\Exceptions\NotFoundException;

Flow::GET()->do(function($req) {
    $userId = $req->param('id');
    
    // Use helper functions
    $logPath = base_path('storage/logs');
    $apiUrl = base_url('api/users');
    $isDebug = env('APP_DEBUG', false);
    
    // Validate
    if (!is_numeric($userId)) {
        abort(400, 'Invalid user ID');
    }
    
    // Find user (simulated)
    $user = findUserById($userId);
    
    if (!$user) {
        throw new NotFoundException('User not found');
    }
    
    // Debug in development
    if ($isDebug) {
        dump($user);
    }
    
    return Response::success($user);
});

```

## Environment File Example

Create a `.env` file in your project root:

```env
# Application Configuration
APP_NAME="My Fluxor App"
APP_ENV=development
APP_DEBUG=true
APP_TIMEZONE=UTC

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=fluxor_db
DB_USERNAME=root
DB_PASSWORD=secret
```

## Notes

- All helper functions are globally available without namespaces
- Functions are only defined if they don't already exist (safe to use)
- The `app()` function can access any registered service
- Use `dd()` for quick debugging, remove before production
- `abort()` is useful for early error handling in routes
- Environment variables support interpolation: `APP_URL=${BASE_URL}/api`
- Boolean values are automatically cast: `true`, `false`, `null`
- Multi-line values are supported with quotes