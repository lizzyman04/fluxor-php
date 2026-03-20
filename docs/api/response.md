# Response Class

The `Response` class builds HTTP responses.

## Factory Methods

### JSON Responses

```php
use Fluxor\Response;

Response::json($data, $status, $headers);
Response::success($data, $message, $status);
Response::error($message, $status, $details);
```

### HTML Responses

```php
Response::html($content, $status, $headers);
Response::view($view, $data, $status);
```

### Other

```php
Response::text($content, $status, $headers);
Response::redirect($url, $status);
Response::download($filePath, $filename);
```

## Examples

### JSON API

```php
Flow::GET()->do(function($req) {
    return Response::json([
        'id' => 1,
        'name' => 'John Doe'
    ]);
});
```

### Success Response

```php
return Response::success(['user' => $user], 'User created', 201);
// Output: {"success":true,"message":"User created","data":{"user":{...}}}
```

### Error Response

```php
return Response::error('Validation failed', 422, ['email' => 'Invalid email']);
// Output: {"success":false,"message":"Validation failed","details":{"email":"Invalid email"}}
```

### View

```php
return Response::view('home', ['title' => 'Welcome']);
```

### View with Data

```php
return Response::view('profile', [
    'user' => $user,
    'posts' => $posts
]);
```

### Redirect

```php
return Response::redirect('/dashboard');
```

### Download

```php
return Response::download('/path/to/file.pdf', 'custom-name.pdf');
```

## Chaining Methods

```php
return Response::json(['data' => 'value'])
    ->status(201)
    ->header('X-Custom', 'value')
    ->withCookie('token', 'abc123', time() + 3600);
```