# Import and call the SDK

## When to use

The first thing to get right in any file that talks to Cloudinary.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

// Reads CLOUDINARY_URL from the environment.
$cloudinary = new Cloudinary();

$result = $cloudinary->uploadApi()->upload('https://res.cloudinary.com/demo/image/upload/sample.jpg');

echo $result['public_id'], PHP_EOL;
```

## The API accessors are methods

`uploadApi()`, `adminApi()`, and `searchApi()` are **methods, not properties**. Calling
them without parentheses is a fatal error:

```php
$cloudinary->uploadApi()->upload($file);   // correct
$cloudinary->uploadApi->upload($file);     // Error: Call to a member function upload() on null
```

The three entry points:

| Accessor | Use for |
|---|---|
| `$cloudinary->uploadApi()` | uploading, renaming, tagging, destroying assets |
| `$cloudinary->adminApi()` | listing and inspecting assets, folders, metadata fields, usage |
| `$cloudinary->searchApi()` | expression-based search across your assets |

URL and tag builders hang off the instance directly — `$cloudinary->image($publicId)`,
`->video()`, `->raw()`, `->imageTag()`, `->videoTag()`.

## Result fields to keep

API calls return `Cloudinary\Api\ApiResponse`, which extends `ArrayObject`. Read fields
with array syntax; call `getArrayCopy()` when you need a plain array to serialize:

```php
$result['public_id'];        // string
$result['secure_url'];       // string
$result->getArrayCopy();     // array, for json_encode() or var_dump()
```

## Namespaces do not always mirror directories

The classmap autoloader means a file's path is not always its namespace. The signing
helper lives at `src/Api/Utils/ApiUtils.php` but is namespaced `Cloudinary\Api`:

```php
use Cloudinary\Api\ApiUtils;          // correct
use Cloudinary\Api\Utils\ApiUtils;    // Error: Class not found
```

When an import fails, check the `namespace` declaration at the top of the source file
rather than inferring it from the directory.

## Troubleshooting

| Symptom | Cause |
|---|---|
| `Call to a member function upload() on null` | Used `uploadApi` instead of `uploadApi()`. |
| `Class "Cloudinary\Cloudinary" not found` | `require 'vendor/autoload.php';` is missing. |
| `ConfigurationException: Invalid configuration` | No credentials — see [Configure Cloudinary](configure-cloudinary.md). |
| `Class "Cloudinary\Api\Utils\ApiUtils" not found` | Namespace is `Cloudinary\Api\ApiUtils`. |

## Related

- [Configure Cloudinary](configure-cloudinary.md)
- [Upload an image](upload-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
