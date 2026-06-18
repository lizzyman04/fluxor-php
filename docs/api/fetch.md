# Fetch Class

The `Fetch` class provides a lightweight, zero-dependency HTTP client for making HTTP requests. It features a fluent interface inspired by JavaScript's fetch API.

## Basic Usage

```php
use Fluxor\Core\Http\Fetch;

// GET request
$users = Fetch::get('https://api.example.com/users')->json();

// POST request
$user = Fetch::post('https://api.example.com/users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
])->json();
```

## HTTP Methods

```php
Fetch::get(string $url): self
Fetch::post(string $url, $body = null): self
Fetch::put(string $url, $body = null): self
Fetch::patch(string $url, $body = null): self
Fetch::delete(string $url, $body = null): self
Fetch::head(string $url): self
Fetch::options(string $url): self
```

## Request Configuration

### Headers

```php
// Single header
Fetch::get('https://api.example.com/users')
    ->header('Authorization', 'Bearer token123')
    ->json();

// Multiple headers
Fetch::get('https://api.example.com/users')
    ->headers([
        'Authorization' => 'Bearer token123',
        'Accept' => 'application/json',
        'X-API-Key' => 'your-api-key'
    ])
    ->json();
```

### Query Parameters

```php
Fetch::get('https://api.example.com/users')
    ->query(['page' => 2, 'limit' => 10])
    ->json();
// URL becomes: https://api.example.com/users?page=2&limit=10
```

### Request Body

```php
// JSON (auto-detected)
Fetch::post('https://api.example.com/users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
])->json();

// Explicit JSON
Fetch::post('https://api.example.com/users')
    ->json(['name' => 'John Doe', 'email' => 'john@example.com'])
    ->send();

// Form data (application/x-www-form-urlencoded)
Fetch::post('https://api.example.com/login')
    ->form(['email' => 'user@example.com', 'password' => 'secret'])
    ->send();

// Multipart form data (file upload)
Fetch::post('https://api.example.com/upload')
    ->multipart(
        ['name' => 'John Doe'],  // Regular fields
        ['avatar' => [           // Files
            'path' => '/path/to/avatar.jpg',
            'filename' => 'avatar.jpg',
            'mime' => 'image/jpeg'
        ]]
    )
    ->send();
```

### Timeout

```php
Fetch::get('https://api.example.com/slow-endpoint')
    ->timeout(60)  // 60 seconds timeout
    ->json();
```

### SSL Verification (Development Only)

```php
// Disable SSL verification (only for local development!)
Fetch::get('https://localhost:8443/api/users')
    ->withoutVerifyingSsl()
    ->json();
```

## Response Handling

### JSON Response

```php
$data = Fetch::get('https://api.example.com/users')->json();
// Returns array (or object if $associative = false)

$user = Fetch::get('https://api.example.com/users/1')
    ->json(false);  // Returns as stdClass
```

### Text Response

```php
$html = Fetch::get('https://example.com')->getText();
```

### Raw Response

```php
$response = Fetch::get('https://api.example.com/users')->send();

$statusCode = $response->getStatusCode();  // 200
$headers = $response->getHeaders();        // Array of headers
$body = $response->getBodyContent();       // Raw response body
```

### Status Code Helpers

```php
$fetch = Fetch::get('https://api.example.com/users');
$response = $fetch->send();

if ($fetch->isOk()) {
    // Status code 2xx
    $data = $response->getBodyContent();
}

$statusCode = $fetch->getStatusCode();  // 200, 404, 500, etc.
```

## Error Handling

### Exception Handling

```php
use Fluxor\Exceptions\HttpException;

try {
    $data = Fetch::get('https://api.example.com/users')->json();
} catch (HttpException $e) {
    echo "HTTP Error: " . $e->getMessage();
    echo "Status Code: " . $e->getStatusCode();
}
```

### Custom Error Callback

```php
$data = Fetch::get('https://api.example.com/users')
    ->onError(function($fetch, $error, $errorNo) {
        error_log("cURL error {$errorNo}: {$error}");
        return ['error' => true, 'message' => 'Request failed'];
    })
    ->json();
```

## Complete Examples

### REST API Client

```php
class ApiClient
{
    private string $baseUrl;
    private string $token;

    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function getUsers(): array
    {
        return Fetch::get("{$this->baseUrl}/users")
            ->header('Authorization', "Bearer {$this->token}")
            ->json();
    }

    public function createUser(array $data): array
    {
        return Fetch::post("{$this->baseUrl}/users", $data)
            ->header('Authorization', "Bearer {$this->token}")
            ->json();
    }

    public function updateUser(int $id, array $data): array
    {
        return Fetch::put("{$this->baseUrl}/users/{$id}", $data)
            ->header('Authorization', "Bearer {$this->token}")
            ->json();
    }

    public function deleteUser(int $id): bool
    {
        $response = Fetch::delete("{$this->baseUrl}/users/{$id}")
            ->header('Authorization', "Bearer {$this->token}")
            ->send();

        return $response->getStatusCode() === 204;
    }
}
```

### File Upload

```php
$result = Fetch::post('https://api.example.com/upload')
    ->multipart(
        ['description' => 'Profile picture'],
        ['avatar' => [
            'path' => '/var/www/uploads/photo.jpg',
            'filename' => 'profile.jpg',
            'mime' => 'image/jpeg'
        ]]
    )
    ->json();
```

### Chained Configuration

```php
$data = Fetch::get('https://api.example.com/search')
    ->query(['q' => 'fluxor', 'limit' => 10])
    ->header('Accept', 'application/json')
    ->timeout(30)
    ->onError(fn($f, $err, $no) => ['error' => $err])
    ->json();
```

## Notes

- The `Fetch` class uses PHP's cURL extension (enabled by default in most PHP installations)
- Zero external dependencies - no Guzzle required
- Automatic JSON encoding/decoding when Content-Type is set
- Follows redirects automatically (up to 5 by default)
- Supports all standard HTTP methods
- Fluent interface for easy configuration