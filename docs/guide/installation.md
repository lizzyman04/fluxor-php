# Installation

## Requirements

- PHP 8.1 or higher
- Composer
- Web server (Apache/Nginx) or PHP built-in server

## Quick Start

Create a new Fluxor project using Composer:

```bash
composer create-project lizzyman04/fluxor-php my-app
cd my-app
```

## Choose a Template

During installation, you'll be prompted to select a template:

- **Default** - Minimal starter with basic routing
- **MVC** - Full MVC with authentication and views
- **API** - Lightweight API with CORS support

## Configuration

Copy `.env.example` to `.env` and adjust settings:

```env
APP_NAME="My Fluxor App"
APP_ENV=development
APP_DEBUG=true
APP_PORT=8000
APP_TIMEZONE=UTC
```

## Running the Application

Start the development server:

```bash
composer dev
```

Visit `http://localhost:8000`