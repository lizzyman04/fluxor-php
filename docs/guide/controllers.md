# Controllers

Controllers organize your application logic into classes.

## Creating a Controller

Create a controller in `src/Controllers/`. Action methods receive the
`Request` as their argument:

```php
<?php

namespace App\Controllers;

use Fluxor\Core\Controller;
use Fluxor\Core\Http\Request;
use Fluxor\Core\Http\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return Response::view('home', [
            'title' => 'Welcome',
            'message' => 'Hello from Controller!'
        ]);
    }

    public function show(Request $request)
    {
        return Response::json([
            'id' => $request->param('id'),
            'data' => 'Some data'
        ]);
    }
}
```

## Using Controllers in Routes

```php
<?php
// app/router/index.php
use App\Controllers\HomeController;
use App\Controllers\UserController;
use Fluxor\Core\Routing\Flow;

Flow::GET()->to(HomeController::class, 'index');

// With dynamic parameters from file-based routing
// app/router/users/[id].php
Flow::GET()->to(UserController::class, 'show');
```

`Flow::to()` calls the controller method with the `Request` as its single
argument. Route parameters (e.g. `id` from `[id].php`) are read from the
request via `$request->param('id')`.

## Available Methods

All controllers extend `Fluxor\Core\Controller`. The request is passed into
each action method.

### Request Access

```php
public function handle(Request $request)
{
    $id      = $request->param('id');      // route param
    $email   = $request->input('email');   // body / query / JSON field
    $allData = $request->all();
    $token   = $request->bearerToken();
}
```

### Using Response Helpers

The `Response` class provides static methods for building responses:

```php
use Fluxor\Core\Http\Response;

// JSON responses
return Response::json($data, $status);
return Response::success($data, $message, $status);
return Response::error($message, $status, $details);

// HTML responses
return Response::view('home', ['title' => 'Welcome']);
return Response::html('<h1>Hello</h1>');
return Response::text('Plain text');

// Redirects
return Response::redirect('/dashboard');

// File downloads
return Response::download('/path/to/file.pdf', 'custom-name.pdf');
```

## Complete Example

```php
<?php

namespace App\Controllers;

use Fluxor\Core\Controller;
use Fluxor\Core\Http\Request;
use Fluxor\Core\Http\Response;
use Fluxor\Exceptions\NotFoundException;

class UserController extends Controller
{
    private array $users = [
        1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
        2 => ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ];

    public function index(Request $request)
    {
        return Response::json(array_values($this->users));
    }

    public function show(Request $request)
    {
        $userId = (int) $request->param('id');

        if (!isset($this->users[$userId])) {
            throw new NotFoundException("User #{$userId} not found");
        }

        return Response::json($this->users[$userId]);
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'email']);

        if (empty($data['name']) || empty($data['email'])) {
            return Response::error('Name and email are required', 422);
        }

        $newId = count($this->users) + 1;
        $this->users[$newId] = ['id' => $newId, ...$data];

        return Response::success($this->users[$newId], 'User created', 201);
    }

    public function update(Request $request)
    {
        $userId = (int) $request->param('id');

        if (!isset($this->users[$userId])) {
            throw new NotFoundException("User #{$userId} not found");
        }

        $data = $request->only(['name', 'email']);
        $this->users[$userId] = [...$this->users[$userId], ...$data];

        return Response::success($this->users[$userId], "User #{$userId} updated");
    }

    public function delete(Request $request)
    {
        $userId = (int) $request->param('id');

        if (!isset($this->users[$userId])) {
            throw new NotFoundException("User #{$userId} not found");
        }

        unset($this->users[$userId]);

        return Response::success(null, "User #{$userId} deleted", 204);
    }
}
```

## Route Files with Controllers

```php
<?php
// app/router/api/users.php
use App\Controllers\UserController;
use Fluxor\Core\Routing\Flow;

Flow::GET()->to(UserController::class, 'index');
Flow::POST()->to(UserController::class, 'store');
```

```php
<?php
// app/router/api/users/[id].php
use App\Controllers\UserController;
use Fluxor\Core\Routing\Flow;

Flow::GET()->to(UserController::class, 'show');
Flow::PUT()->to(UserController::class, 'update');
Flow::DELETE()->to(UserController::class, 'delete');
```

## Notes

- Controllers extend `Fluxor\Core\Controller` and receive the `Request` as the
  argument to each action method (e.g. `public function show(Request $request)`).
- Read route parameters with `$request->param('id')`.
- Response methods are static from the `Response` class.
- You can use any response type (JSON, HTML, redirect, download).
- Controllers are auto-discovered via Composer's PSR-4 autoloading.
- The `src/Controllers/` directory is configured in `composer.json` under the `App\\` namespace.
