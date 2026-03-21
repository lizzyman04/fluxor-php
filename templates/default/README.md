# Fluxor PHP - Default Template

A minimal Fluxor application with basic routing and a simple API example.

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