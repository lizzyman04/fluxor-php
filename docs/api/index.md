# API Reference

Welcome to the Fluxor API Reference. Here you'll find detailed documentation for all core classes and methods.

## Available Classes

| Class | Description |
|-------|-------------|
| [App](/api/app) | Main application entry point |
| [Request](/api/request) | HTTP request handling |
| [Response](/api/response) | HTTP response building |
| [Flow](/api/flow) | Elegant route definitions |
| [Fetch](/api/fetch) | Lightweight HTTP client |
| [Cors](/api/cors) | Cross-Origin Resource Sharing configuration |
| [HttpStatusCode](/api/http-status-code) | HTTP status code constants |
| [Helpers](/api/helpers) | Global helper functions |

## Quick Navigation

### Core Classes
- **App** - Application bootstrap and configuration
- **Request** - Access request data, parameters, and headers
- **Response** - Build JSON, HTML, and redirect responses

### Routing
- **Flow** - Chainable route definitions with HTTP methods
- **HttpStatusCode** - Standard HTTP status code constants

### Security & Configuration
- **Cors** - Configure CORS globally or per-route
- **HttpStatusCode** - Standard HTTP status code constants

### Utilities
- **Helpers** - Global helper functions for common tasks (`env()`, `base_path()`, `config()`, `fetch()`, etc.)

## Examples

### Basic Application

```php
<?php
use Fluxor\Core\App;
use Fluxor\Core\Routing\Flow;
use Fluxor\Core\Http\Response;

$app = new Fluxor\Core\App();
$app->run();

// Define a route
Flow::GET()->do(function($req) {
    return Response::json(['users' => []]);
});
```

### Using Helpers

```php
$baseUrl = base_url();
$config = config('app');
$data = fetch('GET', 'https://api.example.com/users')->json();
```

### Making HTTP Requests

```php
use Fluxor\Core\Http\Fetch;

// Simple GET
$users = Fetch::get('https://api.example.com/users')->json();

// POST with data
$user = Fetch::post('https://api.example.com/users', [
    'name' => 'John Doe'
])->json();

// With headers
$response = Fetch::get('https://api.example.com/me')
    ->header('Authorization', 'Bearer token')
    ->json();
```

## Next Steps

- Browse the [App](/api/app) documentation to learn about configuration
- Check [Flow](/api/flow) for routing examples
- Check [Response](/api/response) for building responses
- Check [Fetch](/api/fetch) for making HTTP requests
- Check [Cors](/api/fetch) for making CORS configuration
- Check [Helpers](/api/helpers) for global helper functions