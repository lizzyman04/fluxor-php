<div align="center">
  <img src="https://raw.githubusercontent.com/lizzyman04/fluxor-php/main/public/assets/img/fluxor.svg" width="120" alt="Fluxor Logo">
  <h1>Fluxor PHP Framework</h1>
  <p><strong>The lightweight PHP framework with file-based routing and elegant Flow syntax.</strong></p>
  
  <!-- Badges do Skeleton (fluxor-php) -->
  [![Latest Stable Version](https://poser.pugx.org/lizzyman04/fluxor-php/v/stable)](https://packagist.org/packages/lizzyman04/fluxor-php) [![Total Downloads](https://poser.pugx.org/lizzyman04/fluxor-php/downloads)](https://packagist.org/packages/lizzyman04/fluxor-php) [![License](https://poser.pugx.org/lizzyman04/fluxor-php/license)](https://packagist.org/packages/lizzyman04/fluxor-php) [![PHP Version Require](https://poser.pugx.org/lizzyman04/fluxor-php/require/php)](https://packagist.org/packages/lizzyman04/fluxor-php)

  <sub>Powered by <a href="https://github.com/lizzyman04/fluxor">Fluxor Core</a> [![Core Version](https://poser.pugx.org/lizzyman04/fluxor/v/stable)](https://packagist.org/packages/lizzyman04/fluxor) [![Core Downloads](https://poser.pugx.org/lizzyman04/fluxor/downloads)](https://packagist.org/packages/lizzyman04/fluxor)</sub>
  
  <p>
    <a href="https://lizzyman04.com/fluxor-php">📚 Documentation</a> •
    <a href="https://github.com/lizzyman04/fluxor">🐙 GitHub </a> •
    <a href="https://packagist.org/packages/lizzyman04/fluxor-php">📦 Packagist</a>
  </p>
</div>

<br>

## 📖 Documentation

**Full documentation available at:** 👉 [**https://lizzyman04.com/fluxor-php**](https://lizzyman04.com/fluxor-php)

The documentation includes:
- 📚 Installation guide
- 🎯 File-based routing (Next.js style)
- 💎 Flow syntax reference
- 🎨 Views and layouts
- 🔧 Controllers and middleware
- ⚙️ Environment configuration
- 📖 Complete API reference with helper functions
- 🚀 Interactive installation guide

## 🚀 Quick Start

```bash
# Create a new Fluxor project
composer create-project lizzyman04/fluxor-php my-app

# Navigate to the project
cd my-app

# Start the development server
composer dev
```

Visit `http://localhost:8000`

## ✨ Features at a Glance

<table align="center">
<tr>
<td width="50%">

### 🎯 File-based Routing
Routes defined by folder structure - like Next.js

```php
app/router/
├── index.php           # GET /
├── api/
│   ├── users.php       # GET /api/users
│   └── users/[id].php  # GET /api/users/123
```

</td>
<td width="50%">

### 💎 Elegant Flow Syntax
Ultra-clean, chainable route definitions

```php
Flow::GET()->do(function($req) {
    $id = $req->param('id');
    return Response::json(['user' => $id]);
});
```

</td>
</tr>
<tr>
<td width="50%">

### 🎨 Views & Layouts
Template system with sections and layouts

```php
View::extend('layouts/main');
View::section('content');
    <h1>Hello World</h1>
View::endSection();
```

</td>
<td width="50%">

### 🔧 Controllers
Organize your application logic

```php
class UserController extends Controller
{
    public function index() {
        return Response::json(User::all());
    }
}
```

</td>
</tr>
<tr>
<td width="50%">

### 🛡️ Middleware & Security
CSRF protection, sessions, request filtering

```php
Flow::use(function($req) {
    if (!$req->isAuthenticated()) {
        return redirect('/login');
    }
});
```

</td>
<td width="50%">

### 🛠️ Utilities & Helpers
Global helpers for common tasks

```php
$url = base_url('api/users');
$path = base_path('storage/logs');
$debug = env('APP_DEBUG', false);
abort(404, 'Not Found');
```

</td>
</tr>
</table>

## 📁 Project Structure

After installation, your project will contain:

```
my-app/
├── app/
│   └── router/            # File-based routes
│       ├── index.php      # GET /
│       └── api/
│           └── users/     # REST API examples
├── public/
│   ├── index.php          # Front controller
│   └── assets/            # Static assets
├── src/
│   └── Views/             # View templates
│       ├── layouts/
│       └── home.php
├── storage/               # Logs, cache, sessions
├── .env                   # Environment configuration
└── composer.json          # Project dependencies
```

## 💎 Quick Example

```php
<?php
// app/router/api/hello/index.php

use Fluxor\Core\Routing\Flow;
use Fluxor\Core\Http\Response;

Flow::GET()->do(fn($req) => 
    Response::success(['message' => 'Hello, ' . $req->input('name', 'World')])
);
```

## 🌟 Key Features

| Feature | Description |
|---------|-------------|
| 🎯 **File-based Routing** | Routes defined by folder structure - like Next.js |
| 💎 **Flow Syntax** | Ultra-clean, chainable route definitions |
| 🎨 **View System** | Layouts, sections, stacks, and partials |
| 🔧 **Controllers** | MVC architecture with base controller |
| 🛡️ **Security First** | Built-in CSRF, XSS protection, secure sessions |
| 🚦 **Middleware** | Flexible request filtering (global + per-route) |
| 🎭 **Error Handling** | Hierarchical error pages (404, 500, etc.) |
| 🔧 **Zero Config** | Auto-detects base path and URL |
| 🌍 **Environment Support** | Built-in .env file parser with type casting |
| 🛠️ **Utilities** | Global helpers (`env()`, `base_path()`, `abort()`, etc.) |
| ⚡ **Performance** | Boot under 10ms, memory footprint ~2MB |
| 📦 **Zero Dependencies** | Just pure PHP, no external packages |

## 📊 Stats & Star History

<div align="center">

| Fluxor PHP (Skeleton) | Fluxor Core (Engine) |
|:---------------------:|:--------------------:|
| ![GitHub Last Commit](https://img.shields.io/github/last-commit/lizzyman04/fluxor-php?style=flat-square) ![Packagist Stars](https://img.shields.io/packagist/stars/lizzyman04/fluxor-php?style=flat-square) | ![Core Release](https://img.shields.io/github/v/release/lizzyman04/fluxor?style=flat-square&label=Release) ![Core Last Commit](https://img.shields.io/github/last-commit/lizzyman04/fluxor?style=flat-square) ![Core Downloads](https://img.shields.io/packagist/dt/lizzyman04/fluxor?style=flat-square) |

</div>

<a href="https://www.star-history.com/?repos=lizzyman04%2Ffluxor-php%2Clizzyman04%2Ffluxor&type=date&legend=bottom-right">
 <picture>
   <source media="(prefers-color-scheme: dark)" srcset="https://api.star-history.com/chart?repos=lizzyman04/fluxor-php%2Clizzyman04/fluxor&type=date&theme=dark&legend=bottom-right" />
   <source media="(prefers-color-scheme: light)" srcset="https://api.star-history.com/chart?repos=lizzyman04/fluxor-php%2Clizzyman04/fluxor&type=date&legend=bottom-right" />
   <img alt="Star History Chart" src="https://api.star-history.com/chart?repos=lizzyman04/fluxor-php%2Clizzyman04/fluxor&type=date&legend=bottom-right" />
 </picture>
</a>

## 🚦 Requirements

- PHP 8.1 or higher
- Composer
- Web server (Apache/Nginx) or PHP built-in server

## 📦 Installation

```bash
composer create-project lizzyman04/fluxor-php my-app
cd my-app
composer dev
```

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Inspired by Next.js file-based routing
- Built with simplicity and performance in mind
- Zero dependencies for maximum control

---

**Fluxor** - Build elegant PHP applications with joy! 🎉