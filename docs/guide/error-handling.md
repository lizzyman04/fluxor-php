# Error Handling

Fluxor provides a robust error handling system with customizable error pages and hierarchical error handlers.

## Custom Error Pages

Create error views in your views directory (`src/Views/errors/`):

```
src/Views/errors/
├── 404.php      # Custom 404 page
├── 500.php      # Custom 500 page
└── common.php   # Generic error template (fallback)
```

Each error view receives two variables:

| Variable | Type | Description |
|----------|------|-------------|
| `$statusCode` | int | HTTP status code (404, 500, etc.) |
| `$message` | string | Error message |

### Example: `src/Views/errors/404.php`

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>The page you requested could not be found.</p>
    <a href="/">Go Home</a>
</body>
</html>
```

### Example: `src/Views/errors/common.php`

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $statusCode ?> - <?= $message ?></title>
</head>
<body>
    <h1><?= $statusCode ?></h1>
    <p><?= $message ?></p>
</body>
</html>
```

## Route-Specific Error Handlers

You can create error handlers for specific route directories:

```
app/router/
├── api/
│   ├── 404.php           # Custom 404 for /api/*
│   └── not-found.php     # Alternative name
├── admin/
│   └── 404.php           # Custom 404 for /admin/*
└── 404.php               # Global 404 handler
```

### Example: `app/router/api/404.php`

```php
<?php
use Fluxor\Response;

return function($request) {
    return Response::json([
        'error' => 'API Endpoint Not Found',
        'path' => $request->path
    ], 404);
};
```

## HTTP Status Code Constants

Fluxor provides `HttpStatusCode` class with all standard HTTP status codes:

```php
use Fluxor\HttpStatusCode;

Response::error('Not Found', HttpStatusCode::NOT_FOUND);
Response::redirect('/home', HttpStatusCode::FOUND);
```

### Available Constants

| Category | Constants |
|----------|-----------|
| **1xx: Informational** | `CONTINUE`, `SWITCHING_PROTOCOLS`, `PROCESSING`, `EARLY_HINTS` |
| **2xx: Success** | `OK`, `CREATED`, `ACCEPTED`, `NO_CONTENT`, etc. |
| **3xx: Redirection** | `MOVED_PERMANENTLY`, `FOUND`, `SEE_OTHER`, `NOT_MODIFIED`, etc. |
| **4xx: Client Errors** | `BAD_REQUEST`, `UNAUTHORIZED`, `FORBIDDEN`, `NOT_FOUND`, `METHOD_NOT_ALLOWED`, `UNPROCESSABLE_ENTITY`, `TOO_MANY_REQUESTS`, etc. |
| **5xx: Server Errors** | `INTERNAL_SERVER_ERROR`, `NOT_IMPLEMENTED`, `BAD_GATEWAY`, `SERVICE_UNAVAILABLE`, etc. |

## Exception Handling

Fluxor provides custom exception classes for different error types:

```php
use Fluxor\Exceptions\AppException;
use Fluxor\Exceptions\HttpException;
use Fluxor\Exceptions\NotFoundException;
use Fluxor\Exceptions\ValidationException;

// Throw a 404 exception
throw new NotFoundException('User not found');

// Throw a validation exception
throw new ValidationException(['email' => 'Invalid email format']);

// Throw a generic HTTP exception
throw new HttpException('Access denied', 403);
```

## Development vs Production

Error responses differ based on environment:

### Development Mode (`APP_DEBUG=true`)

```php
// Returns detailed error information
{
    "error": "Fluxor\\Exceptions\\NotFoundException",
    "message": "User not found",
    "file": "/app/router/users/[id].php",
    "line": 12,
    "trace": [...]
}
```

### Production Mode (`APP_DEBUG=false`)

```php
// Returns generic error message
{
    "error": "Internal Server Error",
    "message": "Something went wrong"
}
```

## JSON Error Responses

When `$request->wantsJson()` returns true, errors are returned as JSON:

```php
// For API requests
{
    "success": false,
    "message": "Not Found",
    "details": null
}
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
    
    // Simulate database lookup
    $user = $this->findUser($userId);
    
    if (!$user) {
        throw new NotFoundException("User #{$userId} not found");
    }
    
    return Response::success($user);
});

Flow::POST()->do(function($req) {
    $data = $req->only(['name', 'email']);
    
    if (empty($data['email'])) {
        throw new ValidationException(['email' => 'Email is required']);
    }
    
    // Create user...
    
    return Response::success(null, 'User created', 201);
});
```

## Error Handler Priority

1. **Route-specific** - `app/router/path/404.php` (most specific)
2. **Directory-specific** - `app/router/directory/404.php`
3. **Global error view** - `src/Views/errors/{code}.php`
4. **Common error view** - `src/Views/errors/common.php`
5. **Fluxor fallback** - Built-in error template

## Notes

- Error views can use any PHP/HTML code
- JSON requests automatically get JSON error responses
- Custom error handlers can return any valid `Response` object
- Development mode shows full stack traces for debugging