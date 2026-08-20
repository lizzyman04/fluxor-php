---
layout: home

hero:
  name: "Fluxor PHP"
  text: "Lightweight MVC framework"
  tagline: File-based routing, elegant Flow syntax, zero bloat — boots in under 10ms.
  image:
    src: /fluxor.svg
    alt: Fluxor
  actions:
    - theme: brand
      text: Get Started
      link: /guide/
    - theme: alt
      text: API Reference
      link: /api/
    - theme: alt
      text: View on GitHub
      link: https://github.com/lizzyman04/fluxor-php

features:
  - title: 🎯 File-based Routing
    details: Folders are URL segments — like Next.js. [param] for dynamic, [...param] for catch-all, (group) for invisible prefixes, scoped 404/405 handlers.
    link: /guide/routing
  - title: 💎 Elegant Flow Syntax
    details: Chainable route definitions. Flow::GET()->do(...), ->to(Controller, 'method'), named routes, per-route CORS and middleware.
    link: /guide/flow-syntax
  - title: 🧭 Controllers
    details: PSR-4 controllers extending Fluxor\Core\Controller. Every action receives the Request as an argument — no hidden state.
    link: /guide/controllers
  - title: 🧅 Middleware
    details: Global or per-route. Return null to continue, a Response to short-circuit, or false for 403. Pass data via request attributes.
    link: /guide/middleware
  - title: 📥 Rich Request API
    details: Typed input helpers, only/except, has/filled/missing, JSON detection, route params, session, bearer token, client IP and CSRF.
    link: /api/request
  - title: 📤 Expressive Responses
    details: json, success, error, view, html, text, redirect, download — all chainable with ->status(), ->header() and ->withCookie().
    link: /api/response
  - title: 🖼️ Views & Layouts
    details: Plain-PHP templates with extend/section/yield, partials via include, and auto-escaping with View::e().
    link: /guide/views
  - title: 🌐 Built-in CORS
    details: Global fluent config or per-route Flow::cors(). Automatic OPTIONS preflight, credential-aware, per-route overrides.
    link: /api/cors
  - title: 🛡️ Security First
    details: CSRF validation on mutating routes, HTML escaping, secure sessions, HTTPS and authentication checks out of the box.
    link: /api/request
  - title: 🚑 Error Handling
    details: Typed exceptions (NotFound, Http, Validation), scoped error pages, and dev stack traces for browser and JSON alike.
    link: /guide/error-handling
  - title: 🔌 HTTP Client
    details: A tiny Fetch client — Fetch::get/post/put/delete(...)->json() with fluent headers for talking to other APIs.
    link: /api/fetch
  - title: 🧰 Global Helpers
    details: app(), base_path(), base_url(), asset(), config(), env(), abort(), redirect(), dd()/dump() — available everywhere, no imports.
    link: /api/helpers

---

## Install in one command

```bash
composer create-project lizzyman04/fluxor-php my-app
cd my-app
composer dev            # → http://localhost:8000
```

Requires **PHP ≥ 8.1**. Routing is powered by the standalone, zero-dependency
[`lizzyman04/file-router`](https://github.com/lizzyman04/file-router) package — nothing else.

## Routes are your folder structure

No route tables to maintain. The file tree under `app/router/` **is** the routing map.

| File | URL |
|---|---|
| `app/router/index.php` | `/` |
| `app/router/about.php` | `/about` |
| `app/router/users/[id].php` | `/users/{id}` |
| `app/router/posts/[cat]/[id].php` | `/posts/{cat}/{id}` |
| `app/router/(admin)/dashboard.php` | `/dashboard` |
| `app/router/api/[...slug].php` | catch-all under `/api/*` |
| `app/router/api/404.php` | 404 handler scoped to `/api/*` |

`[param]` → dynamic segment · `[...param]` → catch-all (array) · `(group)` → invisible prefix.
Priority: **static > dynamic > catch-all**. → [Routing guide](/guide/routing)

## Define behavior with Flow

```php
use Fluxor\Core\Routing\Flow;
use Fluxor\Core\Http\Response;

Flow::GET()->do(fn($req) => Response::json(['ok' => true]));
Flow::POST()->do(fn($req) => Response::success($req->all(), 'Created', 201));

Flow::GET()->name('home')->do(fn($req) => Response::view('home'));
$url = Flow::route('home');                       // generate a named-route URL

Flow::use(fn($req) => $req->isAuthenticated() ? null : Response::redirect('/login'));
Flow::GET()->to(HomeController::class, 'index');  // bind to a controller
```

`->do()` for closures, `->to()` for controllers, `->name()` for named routes,
`Flow::use()` for middleware, `Flow::cors()` for per-route CORS. → [Flow syntax](/guide/flow-syntax)

## Controllers receive the Request

```php
namespace App\Controllers;

use Fluxor\Core\Controller;
use Fluxor\Core\Http\Request;
use Fluxor\Core\Http\Response;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return Response::json(['id' => $request->param('id')]);
    }

    public function store(Request $request)
    {
        if (! $request->validateCsrf()) {
            return Response::error('Invalid CSRF token', 419);
        }
        $data = $request->only(['name', 'email']);
        return Response::success($data, 'Created', 201);
    }
}
```

Each action method takes the `Request` as its argument — explicit, testable, no hidden
state. → [Controllers guide](/guide/controllers)

## A Request API that does the work

```php
$req->param('id');                 // route param from [id].php
$req->input('email', 'default');   // POST / GET / JSON body, with fallback
$req->only(['name', 'email']);     // pick a subset
$req->filled('email');             // exists and non-empty
$req->wantsJson();                 // Accept: application/json
$req->validateCsrf();              // guard mutating routes
$req->isAuthenticated();           // auth check
$req->setAttribute('user', $user); // pass data from middleware → handler
```

→ [Request reference](/api/request)

## Responses for every shape

```php
Response::json($data)->status(201)->header('X-Foo', 'bar');
Response::success($data, 'OK');           // {"success":true,"message":"OK","data":{...}}
Response::error('Nope', 422, $details);   // {"success":false,"message":"Nope","details":{...}}
Response::view('home', ['title' => 'Hi']);
Response::redirect('/dashboard');
Response::download('/tmp/report.pdf', 'report.pdf');
Response::json($d)->withCookie('token', $t, time() + 3600);
```

→ [Response reference](/api/response)

## Views, layouts and partials

```php
<?php View::extend('layouts/main'); ?>
<?php View::section('content'); ?>
    <h1><?= View::e($title) ?></h1>          <!-- auto-escaped -->
    <?= View::include('components/card', ['post' => $post]) ?>
<?php View::endSection(); ?>
```

Layouts pull sections with `View::yield('content')`. → [Views guide](/guide/views)

## Middleware, CORS and typed errors

```php
// Middleware — null continues, a Response stops, false → 403
Flow::use(fn($req) => $req->isAuthenticated() ? null : Response::redirect('/login'));

// CORS — global fluent config (public/index.php, before $app->run())
$app->cors()->allowOrigin('https://example.com')->allowCredentials(true)->enable();
// …or per route (before any Flow::METHOD())
Flow::cors(['allowed_origins' => ['https://example.com'], 'allowed_methods' => ['GET', 'POST']]);

// Typed exceptions map to status codes automatically
use Fluxor\Exceptions\{NotFoundException, ValidationException, HttpException};
throw new NotFoundException('User not found');           // → 404
throw new ValidationException(['email' => 'Invalid']);   // → 422
throw new HttpException('Access denied', 403);           // → 403
```

Preflight `OPTIONS` is handled for you; scoped `404.php` / `not-allowed.php` files override
error pages per directory. → [Middleware](/guide/middleware) · [CORS](/api/cors) · [Error handling](/guide/error-handling)

## Batteries: HTTP client and global helpers

```php
use Fluxor\Core\Http\Fetch;
$user = Fetch::get('https://api.example.com/users/1')
    ->header('Authorization', 'Bearer token')->json();

app();                       // App singleton      | app('view') — a service
base_path('storage/logs');   // absolute path      | base_url('api/users') — full URL
asset('css/app.css');        // public/ asset URL   | config('app_name', 'Fluxor')
env('APP_ENV', 'production');// .env value          | abort(404) / redirect('/home')
dd($var); dump($var);        // dev-time debugging
```

→ [HTTP client](/api/fetch) · [Helpers](/api/helpers)

---

Ready to build? Start with the **[Installation guide](/guide/installation)**, then the
**[full guide](/guide/)** and **[API reference](/api/)**. Full docs live at
[lizzyman04.com/fluxor-php](https://lizzyman04.com/fluxor-php).
