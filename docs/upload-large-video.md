# Upload a large video

## When to use

Uploading a video, or any file large enough that a single request is unreliable —
typically anything above about 20 MB.

## There is no separate large-upload method

`upload()` handles files of any size. When a file exceeds `chunk_size` (20 MB by
default), the SDK splits it into sequential chunked requests automatically. You do not
choose a different method, and there is no `uploadLarge()` in this SDK.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

$result = $cloudinary->uploadApi()->upload(
    '/path/to/large-video.mp4',
    [
        'resource_type' => 'video',
        'public_id'     => 'docs/large-video',
    ]
);

echo $result['public_id'], PHP_EOL;
echo $result['duration'], ' seconds', PHP_EOL;
echo $result['secure_url'], PHP_EOL;
```

Runnable version: [`examples/upload-large-video.php`](../examples/upload-large-video.php).

## `resource_type => 'video'` is required

`upload()` defaults to `image`. A video uploaded without this option fails:

```php
// BadRequest: Invalid image file
$cloudinary->uploadApi()->upload('large-video.mp4');
```

The failure is immediate and explicit — the SDK does not silently store the file as an
opaque blob. Use `'raw'` for non-media files and `'auto'` when the type is unknown at
runtime.

## Result fields to keep

| Field | Why it matters |
|---|---|
| `public_id` | The delivery handle for `$cloudinary->video()`. |
| `duration` | Length in seconds, as a float. |
| `width`, `height` | Source dimensions. |
| `format` | Container format, typically `mp4`. |
| `bytes` | Stored size. |
| `secure_url` | HTTPS URL of the original. |

## Tuning chunk size

```php
$cloudinary = new Cloudinary([
    'cloud' => [/* credentials */],
    'api'   => [
        'chunk_size' => 6000000,   // smaller chunks for an unreliable connection
        'timeout'    => 120,
    ],
]);
```

Smaller chunks mean more requests but less to retransmit after a failure. The default is
20000000 bytes.

## Transcoding happens after upload

The response describes the stored original. Derived versions — other formats, adaptive
streaming renditions, the poster still — are generated when first requested, or eagerly
if you pass `eager`. A URL may take a moment to respond the first time.

For long videos, consider `'eager_async' => true` with a `notification_url` so the
request does not wait for transcoding.

## Troubleshooting

| Symptom | Cause |
|---|---|
| `BadRequest: Invalid image file` | Missing `resource_type => 'video'`. |
| Request times out on a large file | Raise `api.timeout`; lower `api.chunk_size`. |
| `413` or a rejected request | The file exceeds your plan's per-file limit. |
| Upload succeeds, playback 404s briefly | The rendition is still being generated. |
| Memory exhaustion on a huge file | Pass a path or stream, not file contents loaded into a string. |

## Related

- [Transform and deliver a video](transform-and-deliver-video.md)
- [Upload an image](upload-image.md)
- [Configure Cloudinary](configure-cloudinary.md)
