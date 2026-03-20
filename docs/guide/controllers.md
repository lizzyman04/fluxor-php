# Controllers

Controllers organize your application logic into classes.

## Creating a Controller

Create a controller in `src/Controllers/`:

```php
<?php

namespace App\Controllers;

use Fluxor\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home', [
            'title' => 'Welcome',
            'message' => 'Hello from Controller!'
        ]);
    }

    public function show($id)
    {
        return $this->json([
            'id' => $id,
            'data' => 'Some data'
        ]);
    }
}
```

## Using Controllers in Routes

```php
<?php
use App\HomeController;
use App\UserController;
use Fluxor\Flow;

Flow::GET()->to(HomeController::class, 'index');
Flow::GET('/users/{id}')->to(UserController::class, 'show');
```

## Available Methods

All controllers extend `Fluxor\Controller` and have these helpers:

### Response Helpers

```php
$this->json($data, $status);      // JSON response
$this->view($view, $data);        // HTML view
$this->redirect($url);            // Redirect
$this->success($data, $message);  // Success JSON
$this->error($message, $code);    // Error JSON
```

### Request Access

```php
$request = $this->getRequest();
$id = $request->param('id');
$email = $request->input('email');
```

## Example: Auth Controller

```php
<?php

namespace App\Controllers;

use Fluxor\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('auth/login');
    }

    public function login()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        if ($email === 'admin@example.com' && $password === 'secret') {
            $_SESSION['user'] = ['email' => $email];
            return $this->success(null, 'Logged in');
        }

        return $this->error('Invalid credentials', 401);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        return $this->redirect('/');
    }
}