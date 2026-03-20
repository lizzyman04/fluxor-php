# API Reference

Welcome to the Fluxor API Reference. Here you'll find detailed documentation for all core classes and methods.

## Available Classes

| Class | Description |
|-------|-------------|
| [App](/api/app) | Main application entry point |
| [Request](/api/request) | HTTP request handling |
| [Response](/api/response) | HTTP response building |
| [Flow](/api/flow) | Elegant route definitions |
| [HttpStatusCode](/api/http-status-code) | HTTP status code constants |

## Quick Navigation

### Core Classes
- **App** - Application bootstrap and configuration
- **Request** - Access request data, parameters, and headers
- **Response** - Build JSON, HTML, and redirect responses

### Routing
- **Flow** - Chainable route definitions with HTTP methods
- **HttpStatusCode** - Standard HTTP status code constants

## Example

```php
<?php
// Basic usage
use Fluxor\App;
use Fluxor\Flow;
use Fluxor\Response;

$app = new Fluxor\App();
$app->run();

// Define a route
Flow::GET('/api/users')->do(function($req) {
    return Response::json(['users' => []]);
});
```

## Next Steps

- Browse the [App](/api/app) documentation to learn about configuration
- Check [Flow](/api/flow) for routing examples
- See [Response](/api/response) for building responses