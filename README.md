# Cloudinary PHP SDK

[![Tests](https://github.com/cloudinary/cloudinary_php/actions/workflows/test.yaml/badge.svg)](https://github.com/cloudinary/cloudinary_php/actions/workflows/test.yaml)
[![license](https://img.shields.io/github/license/cloudinary/cloudinary_php.svg)](https://github.com/cloudinary/cloudinary_php/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/cloudinary/cloudinary_php.svg)](https://packagist.org/packages/cloudinary/cloudinary_php)

The `cloudinary/cloudinary_php` package is the server-side Cloudinary SDK for PHP. Use it on a server or in a build step to upload assets, build transformation and delivery URLs, and call the Admin API. It holds the API secret, so it handles the operations that can't run in a browser: signed uploads, signed delivery URLs, and asset administration. The current release (3.x) requires PHP 8.0 or later.

## Installation

```bash
composer require "cloudinary/cloudinary_php"
```

This pulls in the bundled transformation builder (`cloudinary/transformation-builder-sdk`) automatically.

## Configuration

Construct a `Cloudinary` instance with no arguments and it reads credentials from the `CLOUDINARY_URL` environment variable:

```bash
CLOUDINARY_URL=cloudinary://<API_KEY>:<API_SECRET>@<CLOUD_NAME>
```

```php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary(); // credentials come from CLOUDINARY_URL in the environment
```

To set them in code instead, pass a configuration array:

```php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'my_cloud_name',
        'api_key'    => 'my_key',
        'api_secret' => 'my_secret',
    ],
]);
```

Keep the API secret on the server. Don't put it in client-side code or commit it to version control.

## Quick examples

### Upload a file

`uploadApi()->upload()` takes a local path, a remote HTTP/HTTPS URL, raw data, or a base64 data URI as its first argument. It returns an array-accessible `ApiResponse` that includes `public_id` and `secure_url`:

```php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary(); // credentials come from CLOUDINARY_URL in the environment

$result = $cloudinary->uploadApi()->upload('my_image.jpg', [
    'public_id' => 'cms/hero', // optional: where the asset lives in your media library
]);

echo $result['public_id'], ' ', $result['secure_url'];
```

### Transform and optimize a delivery URL

`image()` returns a builder you can cast to a string — no network call. This resizes to a 100x150 fill crop and lets Cloudinary pick the format and quality for the requesting browser (`f_auto`, `q_auto`):

```php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Quality;

$cloudinary = new Cloudinary();

echo $cloudinary->image('sample.jpg')
    ->resize(Resize::fill()->width(100)->height(150))
    ->format(Format::auto())
    ->quality(Quality::auto());
// https://res.cloudinary.com/demo/image/upload/c_fill,h_150,w_100/f_auto/q_auto/sample.jpg
```

### Retrieve asset details

`adminApi()->asset()` takes a public ID and returns the asset's metadata, including its format, dimensions, and `secure_url`:

```php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary(); // credentials come from CLOUDINARY_URL in the environment

$asset = $cloudinary->adminApi()->asset('sample');

echo $asset['format'], ' ', $asset['width'], 'x', $asset['height'], ' ', $asset['secure_url'];
```

## For AI agents

`cloudinary/cloudinary_php` is the PHP server-side SDK. Choose it for backend upload, asset administration, search, and signed URL or tag generation, where the API secret stays private. The API surfaces are methods, not properties: call `$cloudinary->uploadApi()->upload(...)`, not `$cloudinary->uploadApi->upload(...)`. For other Cloudinary tasks, choose a different package:

| Task | Package |
|---|---|
| Build transformations at a lower level (already bundled here) | [`cloudinary/transformation-builder-sdk`](https://github.com/cloudinary/php-transformation-builder-sdk) |
| Build delivery URLs with a low-level PHP helper | [`php-url-builder`](https://github.com/cloudinary/php-url-builder) |
| Build delivery URLs in the browser | [`@cloudinary/url-gen`](https://github.com/cloudinary/js-url-gen) |
| Run Cloudinary operations as agent tools | [Cloudinary MCP servers](https://github.com/cloudinary/mcp-servers) |

## Links

- [PHP SDK guide](https://cloudinary.com/documentation/php_integration)
- [Upload](https://cloudinary.com/documentation/php_image_and_video_upload)
- [Asset administration (Admin API)](https://cloudinary.com/documentation/php_asset_administration)
- [Search API](https://cloudinary.com/documentation/search_api)
- [Transformation and API references](https://cloudinary.com/documentation/cloudinary_references)
- [Documentation llms.txt index](https://cloudinary.com/documentation/llms.txt)
- [Package on Packagist](https://packagist.org/packages/cloudinary/cloudinary_php)

Released under the MIT license.
