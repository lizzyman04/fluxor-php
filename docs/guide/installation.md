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

## Interactive Installation

During installation, you'll be asked a few questions to configure your project:

### Author Information
- Your name
- Your email
- Your website (optional)
- Vendor name for Composer package

### Application Configuration
- Application name
- Environment (development/production/testing)
- Debug mode
- Development server port
- Timezone

### Documentation
- Whether to include project documentation
- Documentation folder name (`docs/`, `framework/`, `fluxor/`, or custom)

## After Installation

The installer will:
1. Create all necessary directories
2. Generate `.env` file with your configuration
3. Update `composer.json` with your information
4. Remove installer files

## Project Structure

After installation, your project will contain:

```
my-app/
├── app/
│   └── router/            # File-based routes
├── public/
│   ├── index.php          # Front controller
│   └── assets/            # Static assets (CSS, JS, images)
├── src/
│   └── Views/             # View templates
│       ├── layouts/       # Layout templates
│       └── home.php       # Home page view
├── storage/               # Logs, cache, sessions
├── .env                   # Environment configuration
└── composer.json          # Project dependencies
```

## Need More Features?

For authentication, mailer, and uploader, check out: [github.com/lizzyman04/fluxor-mvc-template](https://github.com/lizzyman04/fluxor-mvc-template)

## Next Steps

- Start building your application by creating routes in `app/router/`
- Create views in `src/Views/`
- Add controllers in `src/Controllers/` (if needed)

## Running the Application

Start the development server:

```bash
composer dev
```

Visit `http://localhost:8000`