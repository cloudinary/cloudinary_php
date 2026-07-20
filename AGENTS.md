# AGENTS.md — cloudinary_php

## What this package is (one line)
Official Cloudinary **PHP server-side SDK**: upload assets, build transformation/delivery URLs and HTML tags, and call the Admin API from your backend — the package that holds your `API_SECRET`.

## When to use this / when NOT to use this
- **Use this when:** code runs on a **server** (Laravel, Symfony, plain PHP, a CLI worker) and needs signed/preset uploads, asset administration (search, rename, tag, delete, folders), or signed delivery URLs and tags where the `API_SECRET` must stay private.
- **Do NOT use this when:** you are building **browser-side** delivery URLs — use [`@cloudinary/url-gen`](https://github.com/cloudinary/js-url-gen) in the frontend, never a PHP package; or you want the autonomous/no-code path — use the [Cloudinary MCP server](https://github.com/cloudinary/mcp-servers).
- **Sibling packages:** this SDK already **bundles** the lower-level transformation builder [`php-transformation-builder-sdk`](https://github.com/cloudinary/php-transformation-builder-sdk) (Composer dep `cloudinary/transformation-builder-sdk: ^2`) — prefer this package over depending on it directly. A separate [`php-url-builder`](https://github.com/cloudinary/php-url-builder) exists as a low-level helper but is **not** a dependency of this SDK. Rule of thumb: server → this package; browser → `@cloudinary/url-gen`.

## Setup
```bash
composer require "cloudinary/cloudinary_php"
```
Required configuration / credentials (the SDK reads `CLOUDINARY_URL` automatically):
```bash
export CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

## Minimal runnable example
```php
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Format;

$cloudinary = new Cloudinary();                  // reads CLOUDINARY_URL from env

// Upload a local file (server-side, signed)
$cloudinary->uploadApi()->upload('my_image.jpg');

// Build a delivery URL for an uploaded asset
echo $cloudinary->image('sample.jpg')
    ->resize(Resize::fill()->width(100)->height(150))
    ->format(Format::auto());
```

## Build / test commands (run these after editing)
Requires PHP 8.0+ and Composer. There is **no** `scripts` block in `composer.json`; invoke the dev binaries in `vendor/bin/` directly. **Only `simple-phpunit` runs in CI** (`.github/workflows/test.yaml`); `phpcs`/`php-cs-fixer` are local-only dev tools, not wired into any workflow.
```bash
composer install            # or `composer update -n` as CI does
vendor/bin/simple-phpunit   # the test suite (symfony/phpunit-bridge) — this is what CI runs; run after any change to src/
vendor/bin/phpcs            # local lint against the committed phpcs.xml (squizlabs/php_codesniffer)
```
Tests hit a real cloud: CI sets `CLOUDINARY_URL` before running (`tools/get_test_cloud.sh`); locally export your own `CLOUDINARY_URL` first or the integration tests will fail.

## Conventions & gotchas
- **Formatter/linter (local only, not in CI):** a PSR-style ruleset is committed as `phpcs.xml` (PHP_CodeSniffer). `friendsofphp/php-cs-fixer` is also a dev dep, but **no `.php-cs-fixer` config is committed at the repo root**, so it runs with its defaults.
- **Autoload:** `src/` is classmap-autoloaded; tests are PSR-4 under `Cloudinary\Test\` → `tests/`. Public namespace root is `Cloudinary\`.
- **`API_SECRET` stays on the server.** That is the entire reason this SDK exists — never ship it to a browser bundle. Signed uploads and signed URLs are server-only.
- **Version support:** current release line is **3.x**, requires **PHP 8.0–8.4** (CI matrix: 8.0, 8.1, 8.2, 8.3, 8.4). 1.x lives on the `support/1.x` branch; see the version table in `README.md`.

## Canonical docs (leave the repo for depth)
- PHP SDK guide: https://cloudinary.com/documentation/php_integration
- Upload: https://cloudinary.com/documentation/php_image_and_video_upload — Admin/asset admin: https://cloudinary.com/documentation/php_asset_administration
- Migration to 2.x/3.x: https://cloudinary.com/documentation/php2_migration
- API & transformation reference: https://cloudinary.com/documentation/cloudinary_references
- MCP server (agent/no-code path): https://github.com/cloudinary/mcp-servers

## Agent / MCP note
If the capability you need is also exposed via the Cloudinary MCP servers, prefer the MCP tool for autonomous task execution and use this SDK for code generation. See cloudinary/mcp-servers.

## Commit / PR conventions
- Ensure tests run locally before opening a PR, and ensure CI (`Tests` workflow, PHP 8.0–8.4 matrix) passes.
- Issues: https://github.com/cloudinary/cloudinary_php/issues. Released under the MIT license.
