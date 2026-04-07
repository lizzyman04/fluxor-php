# Controllers

Controllers organize your application logic into classes.

## Creating a Controller

Create a controller in `src/Controllers/`:

```php
<?php

namespace App\Controllers;

use Fluxor\Controller;
use Fluxor\Response;

class HomeController extends Controller
{
    public function index()
    {
        return Response::view('home', [
            'title' => 'Welcome',
            'message' => 'Hello from Controller!'
        ]);
    }

    public function show($id)
    {
        return Response::json([
            'id' => $id,
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
use Fluxor\Flow;

Flow::GET()->to(HomeController::class, 'index');

// With dynamic parameters from file-based routing
// app/router/users/[id].php
Flow::GET()->to(UserController::class, 'show');
```

## Available Methods

All controllers extend `Fluxor\Controller` and have access to the request object:

### Request Access

```php
$request = $this->getRequest();
$id = $request->param('id');
$email = $request->input('email');
$allData = $request->all();
$token = $request->bearerToken();
```

### Using Response Helpers

The `Response` class provides static methods for building responses:

```php
use Fluxor\Response;

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

use Fluxor\Controller;
use Fluxor\Response;
use Fluxor\Exceptions\NotFoundException;

class UserController extends Controller
{
    private array $users = [
        1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
        2 => ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ];

    public function index()
    {
        return Response::json(array_values($this->users));
    }

    public function show($id)
    {
        $userId = (int) $id;
        
        if (!isset($this->users[$userId])) {
            throw new NotFoundException("User #{$userId} not found");
        }
        
        return Response::json($this->users[$userId]);
    }

    public function store()
    {
        $data = $this->getRequest()->only(['name', 'email']);
        
        if (empty($data['name']) || empty($data['email'])) {
            return Response::error('Name and email are required', 422);
        }
        
        $newId = count($this->users) + 1;
        $this->users[$newId] = ['id' => $newId, ...$data];
        
        return Response::success($this->users[$newId], 'User created', 201);
    }

    public function update($id)
    {
        $userId = (int) $id;
        
        if (!isset($this->users[$userId])) {
            throw new NotFoundException("User #{$userId} not found");
        }
        
        $data = $this->getRequest()->only(['name', 'email']);
        $this->users[$userId] = [...$this->users[$userId], ...$data];
        
        return Response::success($this->users[$userId], "User #{$userId} updated");
    }

    public function delete($id)
    {
        $userId = (int) $id;
        
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
use Fluxor\Flow;

Flow::GET()->to(UserController::class, 'index');
Flow::POST()->to(UserController::class, 'store');
```

```php
<?php
// app/router/api/users/[id].php
use App\Controllers\UserController;
use Fluxor\Flow;

Flow::GET()->to(UserController::class, 'show');
Flow::PUT()->to(UserController::class, 'update');
Flow::DELETE()->to(UserController::class, 'delete');
```

## Notes

- Controllers extend `Fluxor\Controller` and receive the `Request` object via `setRequest()`
- Use `$this->getRequest()` to access the current request
- Response methods are static from the `Response` class
- You can use any response type (JSON, HTML, redirect, download)
- Controllers are auto-discovered via Composer's PSR-4 autoloading
- The `src/Controllers/` directory is configured in `composer.json` under `App\\` namespace