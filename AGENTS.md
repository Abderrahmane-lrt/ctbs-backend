# AGENTS.md

## Project

Cinema Ticket Booking System — REST API for browsing movies, booking tickets, and validating entries. No frontend; API-only backend.

## Stack

Laravel 13 · PHP 8.5 · Sanctum (token auth) · MySQL (prod) / SQLite (dev default) · Pest 4 · Pint · Tailwind v4 · Vite

## Commands

- `composer run dev` — starts server, queue worker, Pail logs, and Vite concurrently
- `composer run setup` — install + migrate + npm build (first-time setup)
- `composer run test` — clears config cache, then runs `php artisan test`
- `php artisan test --compact` — run all tests
- `php artisan test --compact --filter=testName` — run a single test
- `php artisan make:test --pest SomeTest` — create a new test (use `--unit` for unit tests)
- `vendor/bin/pint --dirty --format agent` — format modified PHP files (run before committing)

## Database

- Default `.env.example` uses SQLite (`database.sqlite`). Production targets MySQL.
- Tests run against SQLite in-memory (configured in `phpunit.xml`).
- `RefreshDatabase` is commented out in `tests/Pest.php` — tests do NOT auto-refresh. Add the trait per-test or uncomment globally if needed.

## Domain Model

- **User** — roles: `admin`, `agent`, `client` (string enum in DB, not a PHP enum). Uses Laravel 13 `#[Fillable]`/`#[Hidden]` attributes plus a redundant `$fillable` array.
- **Movie** — has many Showtimes
- **Showtime** — belongs to Movie; stores room, city, location, capacity, start_time, price
- **Ticket** — belongs to User + Showtime; `ticket_code` is a UUID (`Str::uuid()`)

## Auth & Authorization

- Sanctum token-based auth. Send `Authorization: Bearer <token>`.
- Custom `role` middleware registered in `bootstrap/app.php` as alias `role`.
- Usage in routes: `middleware('role:admin')` or `middleware('role:agent,admin')`.
- This is NOT a package — it's `app/Http/Middleware/CheckRole.php`.

## API Routes (`/api`)

- Public: `GET /movies`, `GET /movies/{movie}`, `POST /register`, `POST /login`
- Auth required: `POST /tickets/buy`, `GET /my-tickets`, `POST /logout`
- Agent/Admin: `POST /tickets/validate/{code}`
- Admin only: `POST /movies/create`, `PUT /movies/{movie}`, `DELETE /movies/{movie}`
- No API versioning — routes are flat.
- No Eloquent API Resources — controllers return raw models/arrays.

## Gotchas

- `ShowtimeController` exists but is **empty** (stub) and **not routed**.
- `Ticket.fillable` includes `validated_at` which doesn't exist in the schema — ignore or fix.
- `MovieController@store` validates `duration` but the model fillable is `duration_minutes` — field name mismatch.
- Only `UserFactory` exists. No factories for Movie, Showtime, or Ticket.
- No CI workflow configured (no `.github/` directory).

## Conventions

- Follow existing code style in sibling files when creating new code.
- Use `php artisan make:` for new files; pass `--no-interaction`.
- Run Pint on modified PHP before finalizing.
- Be concise in explanations.
- Only create docs if explicitly requested.

## Docker Environment

- Services: `app` (PHP 8.5-fpm), `web` (Nginx on port 8000), `db` (MySQL 8.0 on port 3306).
- `.env` must use MySQL settings for Docker (uncomment the Docker block in `.env.example`).
- DB credentials: host=`db`, database=`ctbs_db`, user=`root`, password=`secret`.

### Commands

```bash
docker compose up -d                    # Start all containers
docker compose exec app bash            # Enter the app container
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
docker compose down                     # Stop containers
docker compose down -v                  # Stop and remove volumes
```
