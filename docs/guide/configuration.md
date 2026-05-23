# Configuration

## Environment Variables

Fluxor uses a `.env` file for configuration. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

### Available Options

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | "Fluxor App" |
| `APP_ENV` | Environment (development/production/testing) | development |
| `APP_DEBUG` | Debug mode | true |
| `APP_PORT` | Development server port | 8000 |
| `APP_TIMEZONE` | Timezone | UTC |
| `APP_KEY` | Application key (auto-generated) | - |
| `DISABLE_FLUXOR_CACHE` | Disable route cache (set to `true` in dev if needed) | - |

## Route Cache

Fluxor compiles routes to a persistent file-based cache for faster boot times. The cache is stored in `storage/cache/`.

To clear the route cache manually:

```bash
composer clear-router-cache
```

To disable caching entirely (useful when editing routes without restarting the server):

```env
DISABLE_FLUXOR_CACHE=true
```

## Application Configuration

You can also configure paths programmatically:

```php
$app = new Fluxor\App();
$app->setConfig([
    'router_path' => __DIR__ . '/custom/router',
    'views_path' => __DIR__ . '/resources/views',
    'storage_path' => __DIR__ . '/storage',
]);
```

## Config Locking

Protect critical configuration keys from modification:

```php
$app = new Fluxor\App();

// Lock specific keys
$app->lockConfig('router_path', 'views_path');

// Lock all configuration
$app->lockConfig();

// Check if a key is locked
if ($app->isConfigLocked('router_path')) {
    // Cannot modify
}
```

## CORS Configuration

Configure CORS globally or per route:

### Global CORS

```php
$app = new Fluxor\App();
$app->cors()
    ->allowOrigin('https://myfrontend.com')
    ->allowCredentials(true)
    ->enable();
$app->run();
```

### Per-route CORS

```php
// app/router/api/users.php
use Fluxor\Flow;
use Fluxor\Response;

Flow::cors([
    'allowed_origins' => ['https://admin.example.com'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'max_age' => 3600
]);

Flow::GET()->do(fn($req) => Response::json(['users' => []]));
```

## Auto-detection

Fluxor automatically detects:

- **Base Path**: Root directory of your application
- **Base URL**: Current URL (protocol, host, subdirectory)

No configuration needed!