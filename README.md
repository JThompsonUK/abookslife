# Abookslife

- Production:

## Installation

- Laravel: v10
- Inertia
- Vue: v3
- PHP: v8

Run with Docker
```bash
docker compose up //not required for sail

./vendor/bin/sail up
```

Migrate / Seed Database
```bash
./vendor/bin/sail shell
php artisan migrate
php artisan db:seed
exit
```
