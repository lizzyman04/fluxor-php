# App Class

The `App` class is the main entry point for your Fluxor application.

## Basic Usage

```php
$app = new Fluxor\App();
$app->run();
```

## Methods

### `__construct(?string $basePath = null)`

Creates a new application instance. Base path is auto-detected.

```php
$app = new Fluxor\App();
// or
$app = new Fluxor\App('/custom/path');
```

### `run()`

Starts the application and dispatches the request.

### `getBasePath(): string`

Returns the auto-detected base path of the application.

```php
$basePath = $app->getBasePath();
// /var/www/my-app
```

### `getBaseUrl(): string`

Returns the auto-detected base URL.

```php
$baseUrl = $app->getBaseUrl();
// http://localhost:8000/
```

### `getRouter(): Router`

Returns the router instance for middleware registration.

```php
$router = $app->getRouter();
$router->addMiddleware('auth', fn($req) => ...);
```

### `getConfig(): array`

Returns the current configuration array.

```php
$config = $app->getConfig();
// ['router_path' => ..., 'views_path' => ...]
```

### `getService(string $name)`

Retrieves a signuped service from the container.

```php
$view = $app->getService('view');
```

### `isDevelopment(): bool`

Returns `true` if the app is running in development mode.

```php
if ($app->isDevelopment()) {
    // Show debug information
}
```
