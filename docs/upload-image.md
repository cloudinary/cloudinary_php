# Upload an image

## When to use

Getting an image into Cloudinary from server-side code — a local file, a remote URL, a
data URI, or a stream. For uploads initiated by a browser, see
[Sign a browser upload](sign-browser-upload.md).

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

$result = $cloudinary->uploadApi()->upload(
    'https://res.cloudinary.com/demo/image/upload/sample.jpg',
    [
        'public_id' => 'docs/product-shot',
        'tags'      => ['catalog', 'seasonal'],
        'context'   => ['alt' => 'Blue cotton shirt on a hanger'],
    ]
);

echo $result['public_id'], PHP_EOL;
echo $result['secure_url'], PHP_EOL;
```

A local path works identically:

```php
$result = $cloudinary->uploadApi()->upload('/path/to/product-shot.jpg');
```

Runnable version: [`examples/upload-image.php`](../examples/upload-image.php).

## Result fields to keep

| Field | Why it matters |
|---|---|
| `public_id` | The delivery handle. Every transformation URL is built from it. |
| `asset_id` | Immutable identifier. Survives renames — prefer it for lookups you store. |
| `secure_url` | Ready-to-use HTTPS delivery URL of the original. |
| `version` | Cache-busting number; changes on every re-upload to the same `public_id`. |
| `format`, `width`, `height`, `bytes` | What the server actually stored, after any incoming transformation. |
| `etag` | Content hash. Compare to detect whether bytes actually changed. |

Full response for an image: `asset_id`, `public_id`, `version`, `version_id`,
`signature`, `width`, `height`, `format`, `resource_type`, `created_at`, `tags`, `pages`,
`bytes`, `type`, `etag`, `placeholder`, `url`, `secure_url`, `folder`, `access_mode`,
`original_filename`, `api_key`.

## Uploading re-uploads: overwrite is the default

Uploading to an existing `public_id` **replaces** the asset. The `etag` and `version`
change; the `public_id` does not.

To make a collision an error instead:

```php
$cloudinary->uploadApi()->upload($file, [
    'public_id' => 'docs/product-shot',
    'overwrite' => false,
]);
```

To let Cloudinary assign a unique ID, omit `public_id` entirely.

## Choosing the resource type

`upload()` defaults to `resource_type => 'image'`. Uploading a video or a non-media file
without saying so fails loudly:

```php
// BadRequest: Invalid image file
$cloudinary->uploadApi()->upload('clip.mp4');

// Correct
$cloudinary->uploadApi()->upload('clip.mp4', ['resource_type' => 'video']);
$cloudinary->uploadApi()->upload('report.pdf', ['resource_type' => 'raw']);
```

Use `'auto'` when the type is genuinely unknown at runtime.

## Useful upload options

| Option | Effect |
|---|---|
| `public_id` | Sets the delivery handle. Omit for a generated one. |
| `folder` | Stores the asset under a path. |
| `tags` | Array of tags, for later search and bulk operations. |
| `context` | Key-value pairs such as `alt` and `caption`. |
| `overwrite` | `false` makes re-upload to an existing `public_id` an error. |
| `unique_filename` | `false` keeps the original filename as given. |
| `transformation` | Applies an incoming transformation before storing. |
| `eager` | Pre-generates derived versions at upload time. |

## Troubleshooting

| Symptom | Cause |
|---|---|
| `BadRequest: Invalid image file` | The file is a video or non-image; set `resource_type`. |
| `ConfigurationException: Invalid configuration` | Credentials missing — see [Get credentials](get-credentials.md). |
| `AuthorizationRequired` | Key/secret do not match the cloud, or the secret was rotated. |
| Upload succeeds but the asset looks unchanged | You re-uploaded identical bytes; compare `etag`. |
| `NotFound` when uploading a remote URL | Cloudinary must be able to reach the URL from the public internet. |

## Related

- [Upload a large video](upload-large-video.md)
- [Sign a browser upload](sign-browser-upload.md)
- [Transform and deliver an image](transform-and-deliver-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
