# Introduction

Fluxor is a lightweight PHP MVC framework inspired by Next.js file-based routing.

## Philosophy

- **Simplicity**: No magic, transparent code you can read
- **Speed**: Boot under 10ms, zero overhead
- **Elegance**: Beautiful Flow syntax for route definitions
- **Security**: Built-in CSRF, XSS protection, secure sessions
- **Flexibility**: Choose only the features you need during installation

## Why Fluxor?

- ✅ File-based routing like Next.js
- ✅ Elegant Flow syntax
- ✅ Zero configuration (auto-detects paths)
- ✅ Built-in view system with layouts
- ✅ Middleware support
- ✅ Comprehensive error handling
- ✅ Optional features
- ✅ Under 10ms boot time

## Quick Example

```php
<?php
// app/router/index.php
use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function($req) {
    return Response::view('home', [
        'title' => 'Welcome to Fluxor'
    ]);
});
```

## Next Steps

- [Installation](/guide/installation)
- [Routing](/guide/routing)
- [Flow Syntax](/guide/flow-syntax)
- [Views & Layouts](/guide/views)
- [Controllers](/guide/controllers)
- [Middleware](/guide/middleware)
- [Error Handling](/guide/error-handling)