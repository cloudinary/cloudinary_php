# Cloudinary PHP SDK

Upload, transform, optimize, and manage images and videos with Cloudinary from PHP — the `cloudinary/cloudinary_php` package on Packagist.

[![Tests](https://github.com/cloudinary/cloudinary_php/actions/workflows/test.yaml/badge.svg)](https://github.com/cloudinary/cloudinary_php/actions/workflows/test.yaml)
[![Packagist](https://img.shields.io/packagist/v/cloudinary/cloudinary_php.svg)](https://packagist.org/packages/cloudinary/cloudinary_php)
[![Downloads](https://img.shields.io/packagist/dm/cloudinary/cloudinary_php.svg)](https://packagist.org/packages/cloudinary/cloudinary_php/stats)
[![License](https://img.shields.io/packagist/l/cloudinary/cloudinary_php.svg)](LICENSE)

## Install

```bash
composer require cloudinary/cloudinary_php
```

## Quick start

Set your API environment variable (Console > Settings > API Keys):

```bash
export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
```

Upload an image and get an optimized delivery URL:

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;

try {
    $cloudinary = new Cloudinary();

    // Upload a remote image (a local file path works the same way).
    $result = $cloudinary->uploadApi()->upload(
        'https://res.cloudinary.com/demo/image/upload/sample.jpg',
        ['public_id' => 'quickstart-sample']
    );

    echo 'Uploaded: ', $result['public_id'], PHP_EOL;

    // Build a 400x400 auto-cropped URL with automatic format and quality.
    $url = $cloudinary->image($result['public_id'])
        ->resize(Resize::fill(400, 400)->gravity(Gravity::auto()))
        ->delivery(Delivery::format(Format::auto()))
        ->delivery(Delivery::quality(Quality::auto()));

    echo 'Optimized URL: ', $url, PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, 'Quick start failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
```

Save as `quickstart.php` and run `php quickstart.php`. [Create a free account](https://cloudinary.com/users/register_free) if you don't have one — or run `npx @cloudinary/cloud` to [provision one without signing up](docs/get-credentials.md).

`uploadApi()`, `adminApi()`, and `searchApi()` are methods — call them with parentheses.

## Common tasks

- [Get Cloudinary credentials](docs/get-credentials.md)
- [Import and call the SDK](docs/import-and-call.md)
- [Configure Cloudinary](docs/configure-cloudinary.md)
- [Upload an image](docs/upload-image.md)
- [Upload a large video](docs/upload-large-video.md)
- [Sign a browser upload](docs/sign-browser-upload.md)
- [Transform and deliver an image](docs/transform-and-deliver-image.md)
- [Transform and deliver a video](docs/transform-and-deliver-video.md)
- [Search and manage assets](docs/search-and-manage-assets.md)
- [Moderate an upload](docs/moderate-upload.md)
- [Use structured metadata](docs/use-structured-metadata.md)
- [Troubleshoot errors](docs/troubleshoot-errors.md)

Runnable versions live in [`examples/`](examples/) — each is a complete file you can run directly.

## When to use this SDK

Use this package in **PHP server-side code**: uploads, signed operations, asset
administration, search, moderation, and delivery URL generation. It works with any
framework, and with none.

For other jobs, better-fitting tools exist:

- Laravel-native integration with facades and a storage driver: [`cloudinary-labs/cloudinary-laravel`](https://github.com/cloudinary-labs/cloudinary-laravel).
- WordPress, Magento, and similar platforms: [platform integrations](https://cloudinary.com/documentation/integrations) ([md](https://cloudinary.com/documentation/integrations.md)).
- Browser or frontend framework rendering: [frontend SDKs](https://cloudinary.com/documentation/frontend_sdks) ([md](https://cloudinary.com/documentation/frontend_sdks.md)).
- Complete in-browser upload UI: [Upload Widget](https://cloudinary.com/documentation/upload_widget) ([md](https://cloudinary.com/documentation/upload_widget.md)).
- Text-to-image generation and image-to-video: [platform APIs](https://cloudinary.com/documentation/image_generation_addon) ([md](https://cloudinary.com/documentation/image_generation_addon.md)), not wrapped by this package.
- Multi-step media workflow automation: [MediaFlows](https://cloudinary.com/documentation/mediaflows_user_guide) ([md](https://cloudinary.com/documentation/mediaflows_user_guide.md)).
- Interactive agent-driven asset operations: [Cloudinary MCP servers and Skills](https://cloudinary.com/documentation/cloudinary_llm_mcp) ([md](https://cloudinary.com/documentation/cloudinary_llm_mcp.md)).

The full capability map — plus the Skills, MCP servers, and CLI worth setting up first —
is in [docs/platform-capabilities.md](docs/platform-capabilities.md).

## Status and compatibility

Stable, actively maintained. See [CHANGELOG.md](CHANGELOG.md).

| SDK version | PHP |
|-------------|-----|
| 3.x         | 8.0 and later |
| 2.x         | 5.6 – 8.3 (no longer maintained) |
| 1.x         | 5.4 – 7.x (no longer maintained) |

The 1.x series lives on the [`support/1.x`](https://github.com/cloudinary/cloudinary_php/tree/support/1.x) branch. Moving from it? See the [migration guide](https://cloudinary.com/documentation/php2_migration) ([md](https://cloudinary.com/documentation/php2_migration.md)).

## Documentation

- [Bundled task docs](docs/README.md) — ship inside the package, version-matched.
- [PHP SDK guide](https://cloudinary.com/documentation/php_integration) — the full documentation ([md](https://cloudinary.com/documentation/php_integration.md)).
- [Transformation and API reference](https://cloudinary.com/documentation/cloudinary_references) ([md](https://cloudinary.com/documentation/cloudinary_references.md)).

Documentation links in this README point at the browsable HTML page, with an `(md)`
companion link that returns the same page as raw Markdown. Inside `docs/` and `examples/`
the links are Markdown-only, since those files are written to be read by coding agents.
Either form works for any page: add `.md` for Markdown, drop it for HTML.

## For AI coding agents

- Contributing to this repo: read [AGENTS.md](AGENTS.md).
- Using the installed package: the docs in `vendor/cloudinary/cloudinary_php/docs/` match
  your installed version and are the source of truth; start with
  [platform-capabilities](docs/platform-capabilities.md) before assuming a feature exists.

## Support

- SDK bugs and feature requests: [GitHub issues](https://github.com/cloudinary/cloudinary_php/issues)
- Account and platform questions: [Cloudinary support](https://support.cloudinary.com)

## Security

See [SECURITY.md](SECURITY.md) for private vulnerability reporting. Keep your
`api_secret` in server-side code; for client uploads, use the server-signed pattern in
[Sign a browser upload](docs/sign-browser-upload.md).

## License

Released under the MIT license — see [LICENSE](LICENSE). Copyright (c) Cloudinary Ltd.
