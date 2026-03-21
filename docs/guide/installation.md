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

### Feature Selection
You can choose which features to include:

| Feature | Description |
|---------|-------------|
| **Authentication** | Built-in user authentication system with session management and "remember me" |
| **Mailer** | Email sending functionality with SMTP support |
| **Uploader** | Secure file upload handling with hash-based naming to prevent duplicates |

### Feature Configuration

If you select **Authentication**, you'll be prompted for:
- Admin email
- Admin password (default: admin123)

If you select **Mailer**, you'll be prompted for:
- SMTP host
- SMTP port
- SMTP username
- SMTP password

## After Installation

The installer will:
1. Create all necessary directories
2. Generate `.env` and `.env.example` files
3. Update `composer.json` with your information
4. Remove files for features you didn't select

## Project Structure

After installation, your project will contain:

```
my-app/
├── app/
│   ├── core/              # Core helpers
│   └── router/            # File-based routes
├── public/
│   └── index.php          # Front controller
├── src/
│   ├── Controllers/       # Your controllers
│   ├── Models/            # Your models
│   └── Views/             # View templates
├── storage/               # Logs, cache, sessions
├── .env                   # Environment configuration
└── composer.json          # Project dependencies
```

## Next Steps

- Configure your `.env` file with database and other settings
- Start building your application by creating routes in `app/router/`
- Create views in `src/Views/`
- Add controllers in `src/Controllers/` (if needed)

## Running the Application

Start the development server:

```bash
composer dev
```

Visit `http://localhost:8000`