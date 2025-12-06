# SertiKu - AI Coding Instructions

**Project Overview**: SertiKu is a Laravel 12 platform for publishing, managing, and verifying digital certificates with QR Code and blockchain integration. Built with PHP 8.3+, TailwindCSS 4, and Vite.

## Architecture

### Core Stack
- **Backend**: Laravel 12 with PHP 8.3+, following standard MVC architecture
- **Frontend**: Blade templates with TailwindCSS 4.0+ for styling, custom theme variables in `resources/css/app.css`
- **Build System**: Vite for asset bundling with `laravel-vite-plugin` (configured in `vite.config.js`)
- **Authentication**: Google OAuth via `laravel/socialite` (v5.23+)

### Key Service Boundaries
- **Auth Module** (`app/Http/Controllers/Auth/GoogleController.php`): Handles Google OAuth login/logout using Socialite. Includes error handling for SSL cert verification (Windows-specific workaround with `cacert.pem`).
- **User Model** (`app/Models/User.php`): Extends `Authenticatable`, stores `google_id`, `name`, `email`, `avatar`, `password`. Uses `HasFactory` and `Notifiable` traits for testing and notifications.
- **Routes** (`routes/web.php`): Minimal routing—public landing page, auth routes, protected dashboard. Uses route names consistently (`google.redirect`, `google.callback`, `logout`, `dashboard`).

### Views & UI Patterns
- **Blade Layout Components**: Uses `<x-layouts.app>` wrapper component (defined in `resources/views/components/layouts/`)
- **Landing Page**: Long-form hero section with gradient text (`resources/views/landing.blade.php`), responsive grid layout
- **TailwindCSS Custom Theme**: Dark background (`--color-sertiku-bg: #020617`), custom shadow (`--shadow-soft-blue`), Inter font family
- **Convention**: Inline Tailwind classes for responsive design; sections use `max-w-6xl mx-auto px-4` container pattern

## Development Workflow

### Getting Started
```bash
# Full setup: installs PHP/Node deps, generates key, migrates DB, builds assets
composer run-script setup

# Local development (runs 4 concurrent processes)
composer run dev
# Starts: php artisan serve, queue:listen, pail logs, npm run dev

# Build for production
npm run build
```

### Database
- Uses Laravel migrations in `database/migrations/`
- Google OAuth support: `add_google_id_to_users_table` migration already applied
- Seeders in `database/seeders/`; use `DatabaseSeeder` as entry point

### Testing
- PHPUnit (v11.5.3+) configured in `phpunit.xml`
- Tests in `tests/` directory with `Tests\` namespace (PSR-4 autoload)
- Run: `php artisan test` or `./vendor/bin/phpunit`

## Code Patterns & Conventions

### Controllers
- Inherit from `App\Http\Controllers\Controller` base class
- Use `Socialite::driver('google')` for OAuth; catch exceptions with `\Log::error()` and redirect with error messages
- Redirect pattern: `redirect()->intended('/dashboard')` for protected routes

### Models
- Use `$fillable` array to explicitly allow mass assignment (e.g., Google user data)
- Cast dates with `protected function casts()` method (Laravel 11+ syntax, not property)
- Include attribute documentation as `@var` comments for IDE support

### Blade Templates
- Reference assets via Vite's `@vite` directive (auto-handled by `laravel-vite-plugin`)
- Use component syntax for reusables: `<x-component-name attr="value" />`
- Dark theme: use Tailwind dark mode classes or custom `--color-sertiku-bg` color
- Responsive: `max-w-6xl` container, `lg:flex`, `md:text-5xl` for breakpoints

### Configuration
- `.env` file required (copy from `.env.example` in setup)
- Key env vars: `APP_NAME`, `APP_URL`, `APP_DEBUG`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`
- Database credentials: `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

## Common Tasks

### Adding a Protected Route
1. Define in `routes/web.php` within `Route::middleware('auth')->group()`
2. Create controller in `app/Http/Controllers/`
3. Redirect to route using route name: `redirect()->route('route.name')`

### Adding Blade Components
1. Create in `resources/views/components/`
2. Reference as `<x-component-name />` (auto-discovered by Laravel)
3. Use slots for content: `<x-component><x-slot:title>Text</x-slot></x-component>`

### Managing Assets
- CSS: Edit `resources/css/app.css`, Tailwind will auto-include via `@source` directives
- JS: Edit `resources/js/app.js`, import dependencies as ES modules
- Build: `npm run dev` (watch mode) or `npm run build` (production)

## Important Gotchas

- **Windows SSL Cert**: Google OAuth on Windows requires `GuzzleHttp\Client` with `['verify' => 'C:\\php\\cacert.pem']` (see `GoogleController.php`)
- **Queue Listener**: Dev command includes `php artisan queue:listen` to process jobs locally
- **Vite Manifest**: Built assets referenced in production via `manifest.json` in `public/build/`
- **Route Names**: Always use named routes (`->name('route.name')`) for consistency; avoids hardcoding URLs

## File Reference Guide

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/Auth/GoogleController.php` | OAuth logic, user creation/update |
| `app/Models/User.php` | User model with Google OAuth fields |
| `routes/web.php` | Route definitions (minimal, auth-focused) |
| `resources/views/` | Blade templates (landing, dashboard, auth) |
| `resources/css/app.css` | TailwindCSS config with custom theme |
| `vite.config.js` | Asset bundling and refresh config |
| `database/migrations/` | Schema changes, including Google ID column |
| `config/services.php` | OAuth provider credentials (from `.env`) |

## Collaboration Notes

- Language: Mix of English and Indonesian (Bahasa Indonesia) in comments and UI
- Commit early and often; no complex long-running branches observed
- Use `composer run dev` for full-stack debugging; check `pail` logs for server events
