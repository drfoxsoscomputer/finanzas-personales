# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

**Finanzas Personales** is a Laravel 12 web application for personal finance management, built with Filament (a Laravel admin panel framework) and Tailwind CSS for the frontend. The project uses PHP 8.2 with Laravel's modern architecture patterns including Eloquent ORM, validation, middleware, and authentication.

## Common Development Commands

### Setup & Installation
```bash
composer setup    # Initial setup: install dependencies, generate app key, run migrations, build assets
```

### Development
```bash
composer run dev  # Start development environment with: Artisan server, queue listener, and Vite dev server
npm run dev       # Start only the Vite dev server (CSS/JS)
npm run build     # Build frontend assets for production
php artisan serve # Start Laravel development server (port 8000)
```

### Testing
```bash
composer test     # Run full test suite (clears config cache first)
php artisan test --filter=ExampleTest  # Run specific test class
php artisan test tests/Unit/ExampleTest.php  # Run specific test file
php artisan test --group=unit   # Run tests by group
```

### Code Quality & Linting
```bash
php artisan pint  # Format PHP code using Laravel Pint
php artisan pint --test  # Check code format without applying changes
```

### Database
```bash
php artisan migrate        # Run pending migrations
php artisan migrate:fresh  # Drop all tables and re-run migrations
php artisan migrate:rollback  # Rollback the last migration batch
php artisan tinker         # Laravel interactive shell for testing
```

### Artisan Commands
```bash
php artisan list           # List all available commands
php artisan make:migration CreateTableName  # Create a new migration
php artisan make:model ModelName -m         # Create model with migration
php artisan make:controller ControllerName  # Create a new controller
```

## Architecture Overview

### Core Stack
- **Framework**: Laravel 12 with Filament 4.2 (admin panel framework)
- **Frontend**: Tailwind CSS 4 with Vite as build tool
- **Database**: MySQL (configured in .env)
- **Testing**: PHPUnit with Unit and Feature test suites
- **Code Formatting**: Laravel Pint

### Directory Structure

**`app/`** - Application source code
- `Http/Controllers/` - HTTP request handlers
- `Models/` - Eloquent ORM models
- `Providers/` - Service providers (including Filament setup)
- `Filament/` - (to be created) Filament Resources, Pages, and Widgets for the admin panel

**`routes/`** - Application routing
- `web.php` - Web application routes
- `console.php` - Artisan command definitions

**`config/`** - Configuration files for database, app settings, services, etc.

**`database/`** - Database-related files
- `migrations/` - Database schema migrations
- `seeders/` - Data seeders for populating test data
- `factories/` - Model factories for testing

**`resources/`** - Frontend assets
- `css/app.css` - Tailwind CSS entry point
- `js/app.js` - JavaScript entry point
- `views/` - Blade template files

**`tests/`** - Automated tests
- `Unit/` - Unit tests
- `Feature/` - Feature/integration tests

**`storage/`** - Application storage (logs, cache, file uploads)

**`vendor/`** - Composer dependencies

### Filament Admin Panel

The application includes Filament, a powerful Laravel admin panel framework. Configuration is in `app/Providers/Filament/AdminPanelProvider.php`:
- Accessible at `/admin` route
- Requires authentication
- Automatically discovers Resources, Pages, and Widgets from `app/Filament/` directory
- Primary color: Amber
- Includes built-in dashboard with Account widget and Filament info widget

### Frontend Architecture

- **Vite** bundles `resources/css/app.css` and `resources/js/app.js`
- **Tailwind CSS** via `@tailwindcss/vite` plugin for optimized CSS
- **Laravel Vite Plugin** handles hot module replacement during development
- Assets are compiled to `public/` directory

## Environment Configuration

The `.env.example` file defines key settings:
- `APP_LOCALE=es` - Application locale is Spanish
- `DB_CONNECTION=mysql` - Uses MySQL database (update `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` in .env)
- `SESSION_DRIVER=database` - Sessions stored in database
- Test environment uses SQLite in-memory database (configured in `phpunit.xml`)

## Testing Strategy

- Tests configured via `phpunit.xml` with Unit and Feature test suites
- Test database uses in-memory SQLite to avoid affecting production/dev database
- Test environment variables are isolated in `phpunit.xml`
- Example tests provided in both `tests/Unit/` and `tests/Feature/`

## Code Style

The project uses `.editorconfig` to enforce consistent formatting:
- 4-space indentation for PHP, YAML configs, and code
- 2-space indentation for YAML front matter
- UTF-8 encoding with LF line endings
- Trailing whitespace trimmed (except markdown files)

PHP code is formatted with **Laravel Pint** which enforces modern PHP standards.

## Key Dependencies

- **laravel/framework** (^12.0) - Core framework
- **filament/filament** (^4.2) - Admin panel and UI components
- **laravel/tinker** - Interactive shell for debugging
- **tailwindcss** (^4.0) - Utility-first CSS framework
- **vite** (^7.0) - Frontend build tool

Dev dependencies include `laravel/pint` (PHP linting), `phpunit/phpunit` (testing), `fakerphp/faker` (test data generation), and `laravel-lang/common` (localization).
