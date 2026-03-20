# Request Class

The `Request` class represents the current HTTP request.

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `method` | string | HTTP method (GET, POST, etc.) |
| `path` | string | Request path |
| `query` | array | GET parameters |
| `body` | array | POST parameters |
| `json` | array | JSON request body |
| `headers` | array | Request headers |
| `cookies` | array | Cookie values |
| `files` | array | Uploaded files |
| `ip` | string | Client IP address |

## Methods

### Parameter Access

```php
$id = $request->param('id');           // Route parameter
$email = $request->input('email');      // POST/GET/JSON input
$name = $request->input('name', 'default');
$all = $request->all();                  // All input
$only = $request->only(['name', 'email']);
$except = $request->except(['password']);
```

### Checks

```php
if ($request->has('email')) { ... }
if ($request->filled('email')) { ... }
if ($request->missing('email')) { ... }
if ($request->isJson()) { ... }
if ($request->wantsJson()) { ... }
if ($request->isMethod('POST')) { ... }
```

### Authentication

```php
$token = $request->bearerToken();
$user = $request->user();
if ($request->isAuthenticated()) { ... }
```

### CSRF Protection

```php
if (!$request->validateCsrf()) {
    return Response::error('Invalid CSRF token', 419);
}
```

### Session

```php
$value = $request->session('key');
$all = $request->session();
```

### Helpers

```php
$ip = $request->getClientIp();
$ua = $request->getUserAgent();
$url = $request->isSecure();
$path = $request->path;
```