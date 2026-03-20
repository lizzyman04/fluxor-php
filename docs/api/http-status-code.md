# HttpStatusCode Class

Provides HTTP status code constants and helper methods.

## Usage

```php
use Fluxor\HttpStatusCode;

// Use constants
Response::error('Not Found', HttpStatusCode::NOT_FOUND);
Response::redirect('/login', HttpStatusCode::FOUND);

// Get status code message
$message = HttpStatusCode::message(404);  // "Not Found"

// Check status code category
if (HttpStatusCode::isSuccess(200)) { ... }
if (HttpStatusCode::isError(404)) { ... }
```

## Available Constants

### 1xx: Informational

| Constant | Value | Description |
|----------|-------|-------------|
| `CONTINUE` | 100 | Continue |
| `SWITCHING_PROTOCOLS` | 101 | Switching Protocols |
| `PROCESSING` | 102 | Processing |
| `EARLY_HINTS` | 103 | Early Hints |

### 2xx: Success

| Constant | Value | Description |
|----------|-------|-------------|
| `OK` | 200 | OK |
| `CREATED` | 201 | Created |
| `ACCEPTED` | 202 | Accepted |
| `NON_AUTHORITATIVE_INFORMATION` | 203 | Non-Authoritative Information |
| `NO_CONTENT` | 204 | No Content |
| `RESET_CONTENT` | 205 | Reset Content |
| `PARTIAL_CONTENT` | 206 | Partial Content |
| `MULTI_STATUS` | 207 | Multi-Status |
| `ALREADY_REPORTED` | 208 | Already Reported |
| `IM_USED` | 226 | IM Used |

### 3xx: Redirection

| Constant | Value | Description |
|----------|-------|-------------|
| `MULTIPLE_CHOICES` | 300 | Multiple Choices |
| `MOVED_PERMANENTLY` | 301 | Moved Permanently |
| `FOUND` | 302 | Found |
| `SEE_OTHER` | 303 | See Other |
| `NOT_MODIFIED` | 304 | Not Modified |
| `USE_PROXY` | 305 | Use Proxy |
| `SWITCH_PROXY` | 306 | Switch Proxy |
| `TEMPORARY_REDIRECT` | 307 | Temporary Redirect |
| `PERMANENT_REDIRECT` | 308 | Permanent Redirect |

### 4xx: Client Errors

| Constant | Value | Description |
|----------|-------|-------------|
| `BAD_REQUEST` | 400 | Bad Request |
| `UNAUTHORIZED` | 401 | Unauthorized |
| `PAYMENT_REQUIRED` | 402 | Payment Required |
| `FORBIDDEN` | 403 | Forbidden |
| `NOT_FOUND` | 404 | Not Found |
| `METHOD_NOT_ALLOWED` | 405 | Method Not Allowed |
| `NOT_ACCEPTABLE` | 406 | Not Acceptable |
| `PROXY_AUTHENTICATION_REQUIRED` | 407 | Proxy Authentication Required |
| `REQUEST_TIMEOUT` | 408 | Request Timeout |
| `CONFLICT` | 409 | Conflict |
| `GONE` | 410 | Gone |
| `LENGTH_REQUIRED` | 411 | Length Required |
| `PRECONDITION_FAILED` | 412 | Precondition Failed |
| `PAYLOAD_TOO_LARGE` | 413 | Payload Too Large |
| `URI_TOO_LONG` | 414 | URI Too Long |
| `UNSUPPORTED_MEDIA_TYPE` | 415 | Unsupported Media Type |
| `RANGE_NOT_SATISFIABLE` | 416 | Range Not Satisfiable |
| `EXPECTATION_FAILED` | 417 | Expectation Failed |
| `IM_A_TEAPOT` | 418 | I'm a teapot |
| `MISDIRECTED_REQUEST` | 421 | Misdirected Request |
| `UNPROCESSABLE_ENTITY` | 422 | Unprocessable Entity |
| `LOCKED` | 423 | Locked |
| `FAILED_DEPENDENCY` | 424 | Failed Dependency |
| `TOO_EARLY` | 425 | Too Early |
| `UPGRADE_REQUIRED` | 426 | Upgrade Required |
| `PRECONDITION_REQUIRED` | 428 | Precondition Required |
| `TOO_MANY_REQUESTS` | 429 | Too Many Requests |
| `REQUEST_HEADER_FIELDS_TOO_LARGE` | 431 | Request Header Fields Too Large |
| `UNAVAILABLE_FOR_LEGAL_REASONS` | 451 | Unavailable For Legal Reasons |

### 5xx: Server Errors

| Constant | Value | Description |
|----------|-------|-------------|
| `INTERNAL_SERVER_ERROR` | 500 | Internal Server Error |
| `NOT_IMPLEMENTED` | 501 | Not Implemented |
| `BAD_GATEWAY` | 502 | Bad Gateway |
| `SERVICE_UNAVAILABLE` | 503 | Service Unavailable |
| `GATEWAY_TIMEOUT` | 504 | Gateway Timeout |
| `HTTP_VERSION_NOT_SUPPORTED` | 505 | HTTP Version Not Supported |
| `VARIANT_ALSO_NEGOTIATES` | 506 | Variant Also Negotiates |
| `INSUFFICIENT_STORAGE` | 507 | Insufficient Storage |
| `LOOP_DETECTED` | 508 | Loop Detected |
| `NOT_EXTENDED` | 510 | Not Extended |
| `NETWORK_AUTHENTICATION_REQUIRED` | 511 | Network Authentication Required |

## Helper Methods

### `message(int $code): string`

Returns the standard message for a status code:

```php
echo HttpStatusCode::message(404);  // "Not Found"
echo HttpStatusCode::message(200);  // "OK"
echo HttpStatusCode::message(418);  // "I'm a teapot"
```

### Category Check Methods

```php
HttpStatusCode::isInformational(100);  // true (1xx)
HttpStatusCode::isSuccess(200);        // true (2xx)
HttpStatusCode::isRedirection(301);    // true (3xx)
HttpStatusCode::isClientError(404);    // true (4xx)
HttpStatusCode::isServerError(500);    // true (5xx)
HttpStatusCode::isError(404);          // true (4xx or 5xx)
HttpStatusCode::isError(200);          // false
```

## Example Usage

```php
use Fluxor\HttpStatusCode;
use Fluxor\Response;

// API endpoint
Flow::GET('/api/users')->do(function($req) {
    $users = $this->findAllUsers();
    
    if (empty($users)) {
        return Response::error('No users found', HttpStatusCode::NOT_FOUND);
    }
    
    return Response::success($users);
});

// Redirect with appropriate status
Flow::POST('/login')->do(function($req) {
    if ($this->authenticate($req->input('email'), $req->input('password'))) {
        return Response::redirect('/dashboard', HttpStatusCode::FOUND);
    }
    return Response::error('Invalid credentials', HttpStatusCode::UNAUTHORIZED);
});
```

## Notes

- Constants follow HTTP specification standards
- Use constants instead of magic numbers for better code readability
- Category methods are useful for generic error handling