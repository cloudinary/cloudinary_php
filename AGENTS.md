# Contributor guide for coding agents

This file is for agents contributing to this repository. If you are *using* the installed
`cloudinary/cloudinary_php` package in another project, read the bundled docs in
`vendor/cloudinary/cloudinary_php/docs/` instead.

## Commands

```bash
composer install                              # install dependencies
vendor/bin/simple-phpunit --testsuite Unit    # unit tests (mocked, no network)
vendor/bin/simple-phpunit                     # full suite — needs a live cloud
vendor/bin/phpcs                              # PSR-2 lint over src/ and tests/
vendor/bin/phpcbf                             # auto-fix what phpcs can
php examples/upload-image.php                 # run a documentation example
```

Tests need a `CLOUDINARY_URL` in the environment. `bash tools/get_test_cloud.sh` prints a
throwaway one, which is how CI does it:

```bash
export CLOUDINARY_URL=$(bash tools/get_test_cloud.sh)
```

`phpstan.neon` exists but PHPStan is not in `require-dev`; install it separately if you
want to run it. `phpcs` currently reports pre-existing violations in `src/` and `tests/` —
do not mass-fix them in an unrelated pull request.

## Testing

- `tests/Unit/` is mocked and must never perform network calls.
- `tests/Integration/` requires a real or temporary cloud. Do not run it by default, and
  do not add tests there that consume paid add-ons without a skip guard.
- Nondeterministic AI output (captions, tags, moderation verdicts) must be asserted by
  request shape, state transition, and response schema — never by exact output values.
- Some operations are unavailable on throwaway sub-account clouds — folder renaming
  returns `AuthorizationRequired`. Do not build tests or examples that depend on them.
- `examples/` are executable documentation. If you change one, run it against a live cloud
  before committing; they are expected to exit 0 on success and 1 with a readable message
  when credentials are missing.

## Project structure

- `src/Cloudinary.php` — entry point. `uploadApi()`, `adminApi()`, and `searchApi()` are
  **methods**, and `image()`/`video()`/`imageTag()`/`videoTag()` build URLs and tags.
- `src/Api/` — `Admin/`, `Upload/`, `Search/`, `Provisioning/`, plus `Exception/`.
- `src/Configuration/` — configuration objects; input keys are `snake_case`, properties
  are `camelCase`.
- `src/Asset/`, `src/Tag/` — URL builders and HTML tag builders.
- Transformations live in the separate `cloudinary/transformation-builder-sdk` package
  under the `Cloudinary\Transformation` namespace, not in this repo.
- `docs/` — version-matched Markdown task docs shipped in the Composer package.
- `examples/` — runnable task examples, one per docs page, shipped in the package.
- `apidocs/` — Sami API-doc generation tooling. Not shipped. Sami is abandoned and fails
  on PHP 8; the checked-in `apidocs/build/` output is stale.
- `samples/` — legacy sample pages; not part of the tested example set.
- `tools/` — release and test-cloud shell scripts.

Namespaces do not always mirror directories: `src/Api/Utils/ApiUtils.php` declares
`namespace Cloudinary\Api`. Autoloading is a classmap over `src`, so check the
`namespace` line rather than inferring from the path.

## Code style

- PSR-2, enforced by `phpcs`. Four-space indent, one class per file.
- Examples in `examples/` trip PSR-1's "side effects" warning by design — they declare a
  `main()` and call it. Zero errors is the bar there, not zero warnings.
- Public API methods take an options array and return `Cloudinary\Api\ApiResponse`, which
  extends `ArrayObject`:

```php
public function upload(mixed $file, array $options = []): ApiResponse
{
    return $this->uploadAsync($file, $options)->wait();
}
```

- Async variants (`...Async`) return a Guzzle `PromiseInterface`; the sync method wraps it
  with `->wait()`. Add both when adding an API method.

## Git workflow

- Branch from `master`; keep changes focused; one topic per pull request.
- Run `vendor/bin/simple-phpunit --testsuite Unit` before opening a PR.
- Do not rewrite published changelog entries; add new entries at the top.
- The version string lives in `composer.json`, `src/Cloudinary.php` (`const VERSION`), and
  `apidocs/sami_config.php`. `tools/update_version.sh` rewrites all three by exact string
  match — do not reformat those lines.
- Never commit credentials, `.env` files, or generated output.

## Boundaries

**Always**
- Keep `docs/` and `examples/` consistent with the code they document.
- Execute a documentation snippet against a live cloud before committing it; reading the
  source and writing what it appears to do has produced wrong docs repeatedly.
- Keep API secrets out of examples, docs, tests, and fixtures.

**Ask first**
- Changing supported PHP versions, dependencies, or `.gitattributes` `export-ignore`
  entries — the latter decides what ships to users' `vendor/`.
- Renaming or removing any public method or exported symbol.
- Changing release, CI, or publishing configuration.

**Never**
- Commit credentials or real account identifiers.
- Perform live network calls in unit tests.
- Document a Cloudinary platform capability as an SDK method unless this package
  implements it (see `docs/platform-capabilities.md`).
