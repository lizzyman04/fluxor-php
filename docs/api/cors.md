# CORS Class

The `CORS` class provides Cross-Origin Resource Sharing configuration for your Fluxor application. It supports both global and per-route CORS policies.

## Basic Usage

### Global CORS Configuration

```php
use Fluxor\App;

$app = new Fluxor\App();

// Simple global CORS
$app->cors()
    ->allowOrigin('*')
    ->enable();

$app->run();
```

### Per-Route CORS Configuration

```php
// app/router/api/users.php
use Fluxor\Flow;
use Fluxor\Response;

Flow::cors([
    'allowed_origins' => ['https://myapp.com', 'https://admin.myapp.com'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'max_age' => 3600,
    'supports_credentials' => true
]);

Flow::GET()->do(fn($req) => Response::json(['users' => []]));
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `allowed_origins` | array | `['*']` | Allowed origins (use `['*']` for all) |
| `allowed_methods` | array | `['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS']` | Allowed HTTP methods |
| `allowed_headers` | array | `['Content-Type', 'Authorization', 'X-Requested-With', 'User-Agent']` | Allowed request headers |
| `exposed_headers` | array | `[]` | Headers exposed to the browser |
| `max_age` | int | `86400` | How long preflight results can be cached (seconds) |
| `supports_credentials` | bool | `false` | Whether credentials (cookies, auth) are allowed |

## Methods

### Global Configuration

#### `enable(): self`

Enables CORS handling.

```php
$app->cors()->enable();
```

#### `disable(): self`

Disables CORS handling.

```php
$app->cors()->disable();
```

#### `configure(array $config): self`

Configures CORS with an array of options.

```php
$app->cors()->configure([
    'allowed_origins' => ['https://example.com'],
    'allowed_methods' => ['GET', 'POST'],
    'max_age' => 7200
]);
```

#### `allowOrigin(string $origin): self`

Sets a single allowed origin.

```php
$app->cors()->allowOrigin('https://myfrontend.com');
```

#### `allowOrigins(array $origins): self`

Sets multiple allowed origins.

```php
$app->cors()->allowOrigins([
    'https://app.example.com',
    'https://admin.example.com',
    'http://localhost:3000'
]);
```

#### `allowCredentials(bool $allow = true): self`

Enables or disables credentials support.

```php
// Allow cookies and auth headers
$app->cors()->allowCredentials(true);
```

### Per-Route Configuration

#### `Flow::cors(?array $config = null): Cors`

Configures CORS for the current route file.

```php
// app/router/api/public/data.php
use Fluxor\Flow;

// Allow public access
Flow::cors([
    'allowed_origins' => ['*'],
    'allowed_methods' => ['GET']
]);

Flow::GET()->do(fn($req) => Response::json(['public' => 'data']));
```

## Examples

### API with Multiple Origins

```php
$app = new Fluxor\App();

$app->cors()
    ->allowOrigins([
        'https://production.com',
        'https://staging.com',
        'http://localhost:3000'
    ])
    ->allowCredentials(true)
    ->enable();

$app->run();
```

### Admin API with Strict CORS

```php
// app/router/admin/dashboard.php
use Fluxor\Flow;
use Fluxor\Response;

Flow::cors([
    'allowed_origins' => ['https://admin.example.com'],
    'allowed_methods' => ['GET', 'POST'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Admin-Token'],
    'supports_credentials' => true,
    'max_age' => 3600
]);

Flow::GET()->do(function($req) {
    return Response::json(['stats' => ['users' => 150]]);
});
```

### Public API (Read-Only)

```php
// app/router/api/public/posts.php
use Fluxor\Flow;
use Fluxor\Response;

Flow::cors([
    'allowed_origins' => ['*'],
    'allowed_methods' => ['GET'],
    'allowed_headers' => ['Content-Type'],
    'max_age' => 86400
]);

Flow::GET()->do(fn($req) => Response::json([
    ['id' => 1, 'title' => 'Post 1'],
    ['id' => 2, 'title' => 'Post 2']
]));
```

### Route-Specific vs Global

```php
$app = new Fluxor\App();

// Global CORS (applies to all routes)
$app->cors()
    ->allowOrigin('https://default.com')
    ->enable();

// Route-specific overrides global for this route only
// app/router/api/special.php
Flow::cors([
    'allowed_origins' => ['https://special-client.com'],
    'allowed_methods' => ['POST']
]);

Flow::POST()->do(fn($req) => Response::success(null, 'Special endpoint'));
```

### Handling Preflight Requests

CORS preflight requests (OPTIONS) are automatically handled. You don't need to create OPTIONS routes:

```php
// No need to create OPTIONS routes manually!
// Fluxor's CORS handler automatically responds to preflight requests

// Just define your normal routes
Flow::POST()->do(fn($req) => Response::success(null, 'Created'));
// OPTIONS request will automatically return appropriate CORS headers
```

### Complete API Example

```php
<?php
// public/index.php
use Fluxor\App;

$app = new Fluxor\App();

// Global CORS for API
$app->cors()
    ->allowOrigins([
        'https://myapp.com',
        'https://api.myapp.com'
    ])
    ->allowCredentials(true)
    ->enable();

$app->run();
```

```php
<?php
// app/router/api/v1/users.php
use Fluxor\Flow;
use Fluxor\Response;

// Specific CORS for user endpoints
Flow::cors([
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-User-ID'],
    'max_age' => 7200
]);

Flow::GET()->do(fn($req) => Response::json(['users' => []]));
Flow::POST()->do(fn($req) => Response::success(null, 'User created', 201));
```

```php
<?php
// app/router/api/v1/public/status.php
use Fluxor\Flow;
use Fluxor\Response;

// Public endpoint - permissive CORS
Flow::cors([
    'allowed_origins' => ['*'],
    'allowed_methods' => ['GET']
]);

Flow::GET()->do(fn($req) => Response::json(['status' => 'ok']));
```

## Notes

- CORS preflight (OPTIONS) requests are handled automatically
- Route-specific CORS configuration overrides global configuration
- If no CORS is configured, no CORS headers are sent
- For development, you can use `allowOrigin('*')` but avoid in production
- When using `supports_credentials = true`, you cannot use `allowed_origins = ['*']` (browsers will reject)
- The `max_age` value reduces preflight request overhead for repeated requests
- CORS headers are only added when the `Origin` header is present in the request
- Invalid origins receive a 403 Forbidden response

## Security Best Practices

1. **Avoid `'*'` in production** - Specify exact allowed origins
2. **Limit allowed methods** - Only allow what your API actually uses
3. **Use credentials carefully** - Only enable `supports_credentials` when necessary
4. **Set reasonable `max_age`** - Balance between performance and security
5. **Validate origins** - Ensure you're not allowing malicious origins

```php
// Good: Specific origins
$app->cors()->allowOrigins([
    'https://myapp.com',
    'https://staging.myapp.com'
]);

// Bad: Wildcard in production
$app->cors()->allowOrigin('*');  // Avoid in production!

// Good: With credentials
$app->cors()
    ->allowOrigins(['https://myapp.com'])  // Cannot use '*'
    ->allowCredentials(true);

// Bad: Credentials with wildcard (browsers will reject)
$app->cors()
    ->allowOrigin('*')
    ->allowCredentials(true);  // This won't work!
```