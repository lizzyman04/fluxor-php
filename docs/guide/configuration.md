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

## Auto-detection

Fluxor automatically detects:

- **Base Path**: Root directory of your application
- **Base URL**: Current URL (protocol, host, subdirectory)

No configuration needed! 🚀
