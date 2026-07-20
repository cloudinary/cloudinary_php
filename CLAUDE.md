@AGENTS.md

# CLAUDE.md — cloudinary_php

## What this repo is

Official Cloudinary PHP server-side SDK (`cloudinary/cloudinary_php`, 3.x). Handles signed/preset uploads, Admin API calls, asset search, and server-generated transformation URLs and HTML tags — the package that holds your `API_SECRET`.

## Key constraints / gotchas

- **API surfaces are methods, not properties.** Call `$cloudinary->uploadApi()->upload(...)`, not `$cloudinary->uploadApi->upload(...)` — the latter throws.
- **`API_SECRET` stays on the server.** Never ship it in a browser bundle. Use `ApiUtils::signParameters()` to produce a signature the browser posts directly to Cloudinary.
- **`CLOUDINARY_URL` must be in the running process env.** The SDK does not parse `.env` files itself — if you use phpdotenv/Laravel/Symfony, load it before `new Cloudinary()`.
- **No `scripts` block in `composer.json`** — invoke dev tools via `vendor/bin/` directly (see Build commands below).
- **Only `simple-phpunit` runs in CI** (`.github/workflows/test.yaml`). `phpcs` and `php-cs-fixer` are local-only dev deps, not wired into any workflow.
- **No `.php-cs-fixer` config at repo root** — it runs with defaults; the committed ruleset is `phpcs.xml` (PHP_CodeSniffer).
- **Transformation builder is bundled.** `cloudinary/transformation-builder-sdk` is a Composer dep — do not add it separately. Classes live in `Cloudinary\Transformation\`.
- **PHP 8.0+ required** for 3.x. CI matrix: 8.0, 8.1, 8.2, 8.3, 8.4. Legacy 1.x is on the `support/1.x` branch.
- **Integration tests hit a real cloud.** Export `CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME` before running the suite locally, or tests will fail.

## Verified build commands

```bash
composer install                  # install deps (CI uses `composer update -n`)
vendor/bin/simple-phpunit         # test suite (symfony/phpunit-bridge) — what CI runs
vendor/bin/phpcs                  # lint against phpcs.xml (local only)
```

## Autoload layout

- `src/` — classmap-autoloaded; public namespace root `Cloudinary\`
- `tests/` — PSR-4 under `Cloudinary\Test\`
