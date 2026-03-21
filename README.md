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
- Interactive installation guide

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

## ✨ Interactive Installation

During installation, you'll be asked to configure your project:

### Author Information
- Your name, email, website
- Vendor name for Composer package

### Application Configuration
- Application name
- Environment (development/production/testing)
- Debug mode
- Development server port
- Timezone

### Optional Features
You can choose which features to include:

| Feature | Description |
|---------|-------------|
| **🔐 Authentication** | User authentication with sessions, "remember me", and CSRF protection |
| **📧 Mailer** | Email sending with SMTP support and HTML templates |
| **📁 Uploader** | Secure file upload with hash-based naming and duplicate prevention |

The installer automatically removes files for features you don't select, keeping your project clean and focused.

## 📁 Project Structure

After installation, your project will contain:

```
my-app/
├── app/
│   ├── core/              # Core helpers (Auth, Mailer, Uploader)
│   └── router/            # File-based routes (like Next.js)
│       ├── index.php      # GET /
│       ├── about.php      # GET /about
│       └── auth/          # Authentication routes (if enabled)
│           ├── login.php
│           ├── register.php
│           └── logout.php
├── public/
│   ├── index.php          # Front controller
│   └── uploads/           # Uploaded files (if uploader enabled)
├── src/
│   ├── Controllers/       # Application controllers (if auth enabled)
│   ├── Models/            # Database models (if auth enabled)
│   └── Views/             # View templates
│       ├── layouts/       # Layout templates
│       ├── auth/          # Authentication views (if auth enabled)
│       ├── home.php
│       └── about.php
├── storage/               # Logs, cache, sessions
├── .env                   # Environment configuration
└── composer.json          # Project dependencies
```

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
| **🔐 Authentication** | Optional built-in auth with remember me and CSRF |
| **📧 Mailer** | Optional SMTP email support with templates |
| **📁 Uploader** | Optional secure file upload with hash naming |
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