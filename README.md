# Fluxor PHP Framework 🚀

**The lightweight PHP framework with file-based routing and elegant Flow syntax.**

[![Latest Stable Version](https://poser.pugx.org/lizzyman04/fluxor-php/v/stable)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![Total Downloads](https://poser.pugx.org/lizzyman04/fluxor-php/downloads)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![License](https://poser.pugx.org/lizzyman04/fluxor-php/license)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![PHP Version Require](https://poser.pugx.org/lizzyman04/fluxor-php/require/php)](https://packagist.org/packages/lizzyman04/fluxor-php)

## 📖 Documentation

**Full documentation available at:** 👉 [**https://lizzyman04.github.io/fluxor-php**](https://lizzyman04.github.io/fluxor-php)

The documentation includes:
- Installation guide
- File-based routing (Next.js style)
- Flow syntax reference
- Views and layouts
- Controllers and middleware
- Environment configuration
- Complete API reference with helper functions
- Template options (Default, MVC, API)

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

## 📁 Project Structure

```
my-app/
├── app/
│   └── router/              # File-based routes (like Next.js)
│       ├── index.php        # GET /
│       ├── users/
│       │   └── [id].php     # GET /users/{id}
│       └── api/
│           └── hello/
│               └── index.php  # GET /api/hello
├── public/
│   └── index.php            # Front controller
├── src/
│   ├── Controllers/         # Your controllers (MVC template)
│   ├── Views/               # View templates
│   │   ├── layouts/         # Layout templates
│   │   └── errors/          # Custom error pages (404, 500, etc.)
│   └── Helpers/             # Custom helper functions
├── storage/                 # Logs, cache, sessions
├── .env                     # Environment configuration
└── composer.json            # Project dependencies
```

## 🎯 Available Templates

During installation, you can choose from three templates:

| Template | Description |
|----------|-------------|
| **Default** | Minimal starter with basic routing and clean structure |
| **MVC** | Complete MVC with authentication, views, and controllers |
| **API** | Lightweight RESTful API with CORS and JSON responses |

## 💎 Example: Hello World

```php
<?php
// app/router/api/hello/index.php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(fn($req) => 
    Response::success(['message' => 'Hello, ' . $req->input('name', 'World')])
);
```

## 🌟 Key Features

| Feature | Description |
|---------|-------------|
| **🎯 File-based Routing** | Routes defined by folder structure - like Next.js |
| **💎 Flow Syntax** | Ultra-clean, chainable route definitions |
| **🔄 MVC Architecture** | Clean separation with Controllers and Views |
| **🎨 View System** | Layouts, sections, stacks, and partials |
| **🛡️ Security First** | Built-in CSRF, XSS protection, secure sessions |
| **🚦 Middleware** | Flexible request filtering (global + per-route) |
| **🎭 Error Handling** | Hierarchical error pages (404, 500, etc.) |
| **🔧 Zero Config** | Auto-detects base path and URL |
| **🌍 Environment Support** | Built-in .env file parser with type casting |
| **⚡ Performance** | Boot under 10ms, memory footprint ~2MB |

## 📦 Requirements

- PHP 8.1 or higher
- Composer

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Inspired by Next.js file-based routing
- Built with simplicity and performance in mind
- Zero dependencies for maximum control

---

**Fluxor** - Build elegant PHP applications with joy! 🎉