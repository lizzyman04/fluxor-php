# Fluxor PHP — LLM Reference
<!-- This file is loaded only when LLM needs framework-specific information -->
<!-- Docs: /docs | https://lizzyman04.github.io/fluxor-php/ -->

**Lightweight PHP MVC** · File-based routing (Next.js-style) · Elegant Flow syntax · Boot <10ms · Zero external deps

|||
|---|---|
|PHP|≥8.1|
|Boot time|<10ms|
|External deps|0|
|Install|`composer create-project lizzyman04/fluxor-php my-app`|
|Dev server|`composer dev` → `http://localhost:8000`|
|Composer pkg|`lizzyman04/fluxor`|

## Project Layout
```
app/router/ routes — folder=URL segment, [param]=dynamic, (group)=URL-invisible
src/Controllers/ App\Controllers namespace (PSR-4 → src/Controllers/)
src/Views/ PHP templates; src/Views/errors/ for error pages
storage/ logs · sessions · cache
public/index.php entry point (front controller)
.env env config (copy .env.example)
```

## App Bootstrap
<!-- @see /docs/api/app.md | https://lizzyman04.github.io/fluxor-php/api/app -->
```php
use Fluxor\App;
$app = new App(); // base path auto-detected
$app->setConfig([ // optional overrides
 'router_path' => __DIR__ . '/custom/router',
 'views_path' => __DIR__ . '/resources/views',
 'storage_path' => __DIR__ . '/storage',
]);
$app->lockConfig('router_path'); // or lockConfig() for all — immutable after call
$app->run();
// Accessors
$app->getBasePath(); // /var/www/app
$app->getBaseUrl(); // https://example.com/
$app->getRouter(); // Router instance (add middleware here)
$app->getConfig(); // full config array
$app->getService('view'); // registered service by name
$app->isDevelopment(); // true when APP_ENV=development
$app->isDebug(); // true when APP_DEBUG=true (independent of env)
```

## Routing
<!-- @see /docs/guide/routing.md | https://lizzyman04.github.io/fluxor-php/guide/routing -->
|File|URL|
|---|---|
|`app/router/index.php`|`/`|
|`app/router/about.php`|`/about`|
|`app/router/contact/index.php`|`/contact`|
|`app/router/users/[id].php`|`/users/{id}`|
|`app/router/posts/[cat]/[id].php`|`/posts/{cat}/{id}`|
|`app/router/(admin)/dashboard.php`|`/dashboard`|
|`app/router/api/404.php`|404 handler scoped to `/api/*`|
|`app/router/api/[...slug].php`|catch-all under `/api/*`|

`[param]` → dynamic segment → `$req->param('param')`. `[...param]` → catch-all → `$req->param('param')` returns array. `(group)` → URL-invisible prefix. `404.php` / `not-allowed.php` in any dir → scoped error handler. Route priority: static > dynamic > catch-all.

## Flow (Route Definitions)
<!-- @see /docs/guide/flow-syntax.md | https://lizzyman04.github.io/fluxor-php/guide/flow-syntax | /docs/api/flow.md -->
```php
use Fluxor\Flow;
use Fluxor\Response;
Flow::GET()->do(fn($req) => Response::json(['ok' => true]));
Flow::POST()->do(fn($req) => Response::success($req->all(), 'Created', 201));
Flow::PUT()->do(fn($req) => Response::success(null, 'Updated'));
Flow::PATCH()->do(fn($req) => Response::success(null, 'Patched'));
Flow::DELETE()->do(fn($req) => Response::success(null, 'Deleted', 204));
Flow::ANY()->do(fn($req) => ...); // any HTTP method
Flow::GET()->to(HomeController::class, 'index'); // controller binding
Flow::GET()->name('home')->do(fn($req) => ...); // named route
$url = Flow::route('home'); // generate named route URL
Flow::cors([...]); // per-route CORS — call BEFORE Flow::GET/POST/...
Flow::use(fn($req) => null); // middleware — null=continue, Response=stop, false=403
```

## Request
<!-- @see /docs/api/request.md | https://lizzyman04.github.io/fluxor-php/api/request -->
```php
// Properties (direct access)
$req->method; $req->path; $req->query; $req->body;
$req->json; $req->headers; $req->cookies; $req->files; $req->ip;
// Input
$req->param('id'); // route param from [id].php filename
$req->input('email'); // POST / GET / JSON body field
$req->input('name', 'default'); // with fallback
$req->all(); // all input merged
$req->only(['name', 'email']);
$req->except(['password']);
// Checks
$req->has('email'); // key exists (any value)
$req->filled('email'); // exists + non-empty
$req->missing('email');
$req->isJson(); // Content-Type: application/json
$req->wantsJson(); // Accept: application/json
$req->isMethod('POST');
// Auth & Security
$req->bearerToken(); // strips "Bearer " from Authorization header
$req->isAuthenticated();
$req->validateCsrf(); // bool — call on POST/PUT/PATCH/DELETE routes
$req->session('key'); // single session value
$req->session(); // all session data
// Metadata
$req->getClientIp();
$req->getUserAgent();
$req->isSecure(); // true = HTTPS
// Attributes (middleware → handler data passing)
$req->setAttribute('user', $user); // set in middleware
$req->getAttribute('user'); // get in route handler
```

## Response
<!-- @see /docs/api/response.md | https://lizzyman04.github.io/fluxor-php/api/response -->
```php
use Fluxor\Response;
Response::json($data, $status=200, $headers=[]);
Response::success($data, $message='OK', $status=200); // {"success":true,"message":"...","data":{}}
Response::error($message, $status=500, $details=null); // {"success":false,"message":"...","details":{}}
Response::view('template', ['key' => 'val'], $status=200);
Response::html('<h1>x</h1>', $status=200, $headers=[]);
Response::text('plain text', $status=200, $headers=[]);
Response::redirect('/path', $status=302);
Response::download('/path/file.pdf', 'download-name.pdf');
// Chaining
Response::json($data)->status(201)->header('X-Foo', 'bar')->withCookie('k', 'v', time()+3600);
```

## Controllers
<!-- @see /docs/guide/controllers.md | https://lizzyman04.github.io/fluxor-php/guide/controllers -->
```php
namespace App\Controllers; // PSR-4: src/Controllers/
use Fluxor\Controller;
use Fluxor\Response;
use Fluxor\Exceptions\NotFoundException;
class UserController extends Controller {
 public function index() { return Response::json([]); }
 public function show($id) { return Response::json(['id' => $id]); } // $id from route param
 public function store() {
 $data = $this->getRequest()->only(['name', 'email']);
 return Response::success($data, 'Created', 201);
 }
 public function update($id) { ... }
 public function delete($id) { return Response::success(null, "Deleted #{$id}", 204); }
}
// $this->getRequest() → Request instance inside any controller method
```
Route file binding:
```php
// app/router/api/users/[id].php
use App\Controllers\UserController;
use Fluxor\Flow;
Flow::GET()->to(UserController::class, 'show');
Flow::PUT()->to(UserController::class, 'update');
Flow::DELETE()->to(UserController::class, 'delete');
```

## Middleware
<!-- @see /docs/guide/middleware.md | https://lizzyman04.github.io/fluxor-php/guide/middleware -->
```php
// Global — runs on every request, in registration order
Flow::use(function($req) {
 if (!$req->isAuthenticated()) return Response::redirect('/login');
 return null; // continue to next middleware / route
});
// Route-specific — call use() immediately before the route definition
Flow::use($authMiddleware);
Flow::GET()->to(Controller::class, 'method');
// Via router object
$app->getRouter()->addMiddleware('cors', fn($req) => null);
```
Returns: `null` → continue · `Response` → stop & send · `false` → 403 Forbidden.

## Views & Layouts
<!-- @see /docs/guide/views.md | https://lizzyman04.github.io/fluxor-php/guide/views -->
```php
// src/Views/home.php — rendered by Response::view('home', $data)
<h1><?= $title ?></h1>
// Layout — src/Views/layouts/main.php
<?php use Fluxor\View; ?>
<html><body><?= View::yield('content') ?><?= View::yield('sidebar', '') ?></body></html>
// View extending layout
<?php View::extend('layouts/main'); ?>
<?php View::section('content'); ?>
 <p>Hello</p>
<?php View::endSection(); ?>
// Partial
<?= View::include('components/header', ['title' => 'X']) ?>
// Output escaping
<?= View::e($userInput) ?> // HTML-escaped (safe)
<?= View::raw($html) ?> // raw HTML — use carefully
// Error views: src/Views/errors/404.php, 500.php, common.php
// Variables available: $statusCode (int), $message (string)
```

## Error Handling
<!-- @see /docs/guide/error-handling.md | https://lizzyman04.github.io/fluxor-php/guide/error-handling -->
```php
use Fluxor\Exceptions\{AppException, HttpException, NotFoundException, ValidationException};
throw new NotFoundException('User not found'); // → 404
throw new ValidationException(['email' => 'Invalid format']); // → 422
throw new HttpException('Access denied', 403);
// Scoped 404 handler — app/router/api/404.php
return function($request) {
 return Response::json(['error' => 'Not found', 'path' => $request->path], 404);
};
```
Handler priority (most → least specific):
1. `app/router/[path]/404.php` (route-specific)
2. `app/router/[dir]/404.php` (directory-scoped)
3. `src/Views/errors/{code}.php`
4. `src/Views/errors/common.php`
5. Fluxor built-in

`APP_ENV=development` + `APP_DEBUG=true` → HTML error page with full stack trace (browser) or detailed JSON (API). `APP_DEBUG=false` → generic message. `$req->wantsJson()` → error always returned as JSON regardless of views.

## CORS
<!-- @see /docs/api/cors.md | https://lizzyman04.github.io/fluxor-php/api/cors -->
```php
// Global (public/index.php — before $app->run())
$app->cors()
 ->allowOrigin('https://example.com')
 ->allowOrigins(['https://a.com', 'https://b.com'])
 ->allowCredentials(true) // ⚠ cannot combine with '*' origin
 ->configure(['max_age' => 7200, 'allowed_methods' => ['GET', 'POST']])
 ->enable();
// Per-route (in router file, before any Flow::METHOD() call)
Flow::cors([
 'allowed_origins' => ['https://example.com'], // default: ['*']
 'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
 'allowed_headers' => ['Content-Type', 'Authorization'],
 'exposed_headers' => [],
 'max_age' => 86400,
 'supports_credentials' => false,
]);
```
Preflight (OPTIONS) handled automatically — no manual OPTIONS routes needed. Per-route config overrides global. Invalid origin → 403. `supports_credentials=true` + `'*'` origin = browsers reject.

## Configuration
<!-- @see /docs/guide/configuration.md | https://lizzyman04.github.io/fluxor-php/guide/configuration -->
`.env` keys: `APP_NAME` · `APP_ENV` · `APP_DEBUG` · `APP_PORT` · `APP_TIMEZONE` · `APP_KEY` · `DISABLE_FLUXOR_CACHE`
Route cache stored in `storage/cache/`. Clear with `composer clear-router-cache`. Set `DISABLE_FLUXOR_CACHE=true` to skip caching.

## HttpStatusCode
<!-- @see /docs/api/http-status-code.md | https://lizzyman04.github.io/fluxor-php/api/http-status-code -->
```php
use Fluxor\HttpStatusCode;
// Common constants (full list in /docs/api/http-status-code.md)
HttpStatusCode::OK // 200
HttpStatusCode::CREATED // 201
HttpStatusCode::NO_CONTENT // 204
HttpStatusCode::FOUND // 302
HttpStatusCode::NOT_MODIFIED // 304
HttpStatusCode::BAD_REQUEST // 400
HttpStatusCode::UNAUTHORIZED // 401
HttpStatusCode::FORBIDDEN // 403
HttpStatusCode::NOT_FOUND // 404
HttpStatusCode::UNPROCESSABLE_ENTITY // 422
HttpStatusCode::TOO_MANY_REQUESTS // 429
HttpStatusCode::INTERNAL_SERVER_ERROR // 500
// Helpers
HttpStatusCode::message(404); // "Not Found"
HttpStatusCode::isSuccess(200); // true (2xx)
HttpStatusCode::isRedirection(301); // true (3xx)
HttpStatusCode::isClientError(422); // true (4xx)
HttpStatusCode::isServerError(500); // true (5xx)
HttpStatusCode::isInformational(100); // true (1xx)
HttpStatusCode::isError(404); // true (4xx or 5xx)
```

## Global Helpers
<!-- @see /docs/api/helpers.md | https://lizzyman04.github.io/fluxor-php/api/helpers -->
```php
app() // App singleton
app('view') // registered service by name
base_path('storage/logs') // absolute path from project root
base_url('api/users') // full URL from app base
asset('css/app.css') // URL → public/assets/css/app.css
config('app_name', 'Fluxor') // app config value with fallback
env('APP_ENV', 'production') // .env value with fallback
fetch('GET', 'https://api.example.com/users')->json()
fetch('POST', 'https://api.example.com/users', ['name' => 'X'])->json()
http_status_message(404) // "Not Found"
abort(404) // throws HttpException (stops execution)
abort(403, 'Access denied')
redirect('/dashboard') // returns Response::redirect(...)
dd($var, $var2) // dump + die — dev only, remove before prod
dump($var) // dump, continues execution
```

## HTTP Client (Fetch)
<!-- @see /docs/api/fetch.md | https://lizzyman04.github.io/fluxor-php/api/fetch -->
```php
use Fluxor\Fetch;
Fetch::get($url)->json();
Fetch::post($url, ['key' => 'val'])->json();
Fetch::put($url, $data)->json();
Fetch::delete($url)->json();
Fetch::get($url)->header('Authorization', 'Bearer token')->json();
```

## Key Constraints
- `[param]` in filename → `$req->param('param')` not `$req->input()`
- `[...param]` in filename → catch-all; `$req->param('param')` returns array of segments
- `(group)` dirs are stripped from URL, used for logical organization only
- `Flow::use()` registration order = execution order
- `Flow::cors()` must be called BEFORE any `Flow::GET()/POST()/...` in the same file
- `supports_credentials=true` requires explicit origin list — never `'*'`
- CSRF: call `$req->validateCsrf()` manually on mutating routes (POST/PUT/PATCH/DELETE)
- Controllers must extend `Fluxor\Controller`; use `$this->getRequest()` for the request
- PSR-4: `App\Controllers` → `src/Controllers/`; auto-discovered by Composer
- All helpers are globally available, no namespace needed