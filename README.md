# De Bazaar

De Bazaar is a Laravel marketplace application for browsing and trading advertisements. It combines sales, rentals and auctions with user profiles and business pages. The repository's contribution history includes work by multiple contributors.

## Features

- Browse advertisements and view details; permitted sellers can create listings or import them from CSV.
- Purchase or rent listings, place auction bids, and view sales, purchases and calendars.
- Save favorites and review advertisements or users.
- Manage a business page, including its theme, URL and configurable components.
- Register and manage a profile, accept contracts, switch between Dutch and English, and play minigames.

Some actions require authentication, contract acceptance or specific permissions; see [routes/web.php](routes/web.php) for the route definitions.

## Stack

PHP 8.2+, Laravel 11, Blade, Alpine.js, Tailwind CSS and Vite. The project uses Composer and npm; Laravel Dusk and PHPUnit are included as development dependencies. See [composer.json](composer.json) and [package.json](package.json) for the complete dependencies.

## Local setup

Install PHP with the extensions required by Composer, Composer, Node.js and npm, and a database supported by Laravel. From the repository root:

```sh
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your local database connection in `.env`, create the database, then run:

```sh
php artisan migrate
php artisan storage:link
npm run dev
```

In another terminal, run `php artisan serve` and open the local URL it prints. The optional `php artisan db:seed` populates the database with example data; inspect [DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) before using it. Use local credentials and keep `.env` private. `npm run build` builds frontend assets for deployment.

## Tests

The repository contains PHPUnit feature tests for authentication and profiles, example unit/feature tests, and Dusk browser tests for authentication, advertisements and locale switching.

```sh
php artisan test
php artisan dusk
```

Configure an isolated test database before running tests. Dusk also requires a compatible browser and ChromeDriver and a separate local browser-test environment; do not point it at a database containing data you need. These commands are documented here without asserting a test result.
