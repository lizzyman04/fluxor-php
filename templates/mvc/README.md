# Fluxor MVC Template

A complete MVC application template with authentication, database models, and views.

## Features

- ✅ MVC Architecture
- ✅ Authentication System
- ✅ Database Models
- ✅ View Templates with Layouts
- ✅ Form Validation
- ✅ Flash Messages
- ✅ Pagination
- ✅ Session Management

## Installation

```bash
composer create-project lizzyman04/fluxor-php my-app
cd my-app
```

## Routes

| URL | Method | File |
|-----|--------|------|
| `/` | GET | `app/router/index.php` |
| `/about` | GET | `app/router/about.php` |
| `/contact` | GET/POST | `app/router/contact/index.php` |
| `/api/hello` | GET | `app/router/api/hello/index.php` |

## Development

```bash
composer dev
```

Visit http://localhost:8000