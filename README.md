# Fluxor PHP Framework 🚀

**The lightweight PHP framework with file-based routing and elegant Flow syntax.**

[![Latest Stable Version](https://poser.pugx.org/lizzyman04/fluxor-php/v/stable)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![Total Downloads](https://poser.pugx.org/lizzyman04/fluxor-php/downloads)](https://packagist.org/packages/lizzyman04/fluxor-php)
[![License](https://poser.pugx.org/lizzyman04/fluxor-php/license)](https://packagist.org/packages/lizzyman04/fluxor-php)

## Quick Start

```bash
composer create-project lizzyman04/fluxor-php meu-app
cd meu-app
php -S localhost:8000 -t public
```

Visit `http://localhost:8000`

## 📁 Project Structure

```
meu-app/
├── app/
│   └── router/          # File-based routes (like Next.js)
│       ├── page.php     # GET /
│       └── api/
│           └── hello/
│               └── index.php  # GET /api/hello
├── public/
│   └── index.php        # Front controller
├── src/
│   ├── Controllers/     # Your controllers
│   └── Views/           # View templates
├── storage/             # Logs, cache, sessions
├── .env                 # Environment config
└── composer.json
```

## 🎯 File-Based Routing

Create routes by adding files:

- `app/router/page.php` → `/`
- `app/router/about.php` → `/about`
- `app/router/posts/[slug]/index.php` → `/posts/{slug}`
- `app/router/api/users/[id].php` → `/api/users/{id}`

## 💎 Flow Syntax

```php
<?php
// app/router/api/hello/index.php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(fn($req) => 
    Response::success(['message' => 'Hello, ' . $req->input('name', 'World')])
);

return Flow::execute($req);
```

## 📚 Documentation

Full documentation available at [https://github.com/lizzyman04/fluxor](https://github.com/lizzyman04/fluxor)

## 📄 License

MIT