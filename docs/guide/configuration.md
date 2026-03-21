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

### Authentication Configuration (if enabled)

| Variable | Description | Default |
|----------|-------------|---------|
| `AUTH_SECRET_KEY` | Secret key for token generation | auto-generated |
| `AUTH_SESSION_EXPIRY` | Session expiry in seconds | 1800 |
| `AUTH_REMEMBER_EXPIRY` | Remember token expiry | 2592000 |

### Mailer Configuration (if enabled)

| Variable | Description |
|----------|-------------|
| `MAIL_HOST` | SMTP server hostname |
| `MAIL_PORT` | SMTP port (587 for TLS, 465 for SSL) |
| `MAIL_USERNAME` | SMTP username |
| `MAIL_PASSWORD` | SMTP password |
| `MAIL_FROM_ADDRESS` | Default sender email |
| `MAIL_FROM_NAME` | Default sender name |

### Uploader Configuration (if enabled)

| Variable | Description | Default |
|----------|-------------|---------|
| `UPLOAD_MAX_SIZE` | Maximum file size in bytes | 5242880 (5MB) |
| `UPLOAD_ALLOWED_TYPES` | Allowed file extensions | jpg,jpeg,png,gif,webp,pdf,doc,docx |

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