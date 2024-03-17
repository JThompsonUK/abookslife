# Abookslife
Add a book to the site and follow it's journey. Similar to when you checkout a book at a library. See who viewed your book and when and where it was read. Either stick the QR code in your book, or write the unique reference. This will allow people to access your individual book record.

## Installation

- Laravel: v10
- Inertia
- Vue: v3
- PHP: v8

## Run locally with Laravel Sail

<a href="https://laravel.com/docs/10.x/sail">Laravel Sail</a> is a light-weight command-line interface for interacting with Laravel's default Docker development environment. 

```bash
./vendor/bin/sail up
```

Migrate / Seed Database
```bash
./vendor/bin/sail shell
php artisan migrate
php artisan db:seed
npm run watch
```

## Authentication

```bash
Test user:
name: John Smith
email: johnsmith@test.com
password: test
```