# Fluxor PHP Framework 🚀

**The lightweight PHP framework with file-based routing and elegant Flow syntax.**

[![Latest Stable Version](https://poser.pugx.org/lizzyman04/fluxor-php/v/stable)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![Total Downloads](https://poser.pugx.org/lizzyman04/fluxor-php/downloads)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![License](https://poser.pugx.org/lizzyman04/fluxor-php/license)](https://packagist.org/packages/lizzyman04/fluxor-php)

## 📖 Documentation

**Full documentation available at:** 👉 [**https://lizzyman04.github.io/fluxor-php**](https://lizzyman04.github.io/fluxor-php)

The documentation includes:
- Installation guide
- File-based routing
- Flow syntax reference
- Views and layouts
- Controllers and middleware
- API reference
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
│   └── router/          # File-based routes (like Next.js)
│       ├── page.php     # GET /
│       └── api/
│           └── hello/
│               └── index.php  # GET /api/hello
├── public/
│   └── index.php        # Front controller
├── src/
│   ├── Controllers/     # Your controllers (MVC template)
│   └── Views/           # View templates (MVC template)
├── storage/             # Logs, cache, sessions
├── .env                 # Environment configuration
└── composer.json        # Project dependencies
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

## 📦 Requirements

- PHP 8.2 or higher
- Composer

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

---

**Fluxor** - Build elegant PHP applications with joy! 🎉