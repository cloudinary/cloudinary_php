# Transform and deliver a video

## When to use

Building a delivery URL or a `<video>` tag for a video already in Cloudinary. As with
images, transformations happen at delivery time and the original is untouched.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;

$cloudinary = new Cloudinary();

$url = $cloudinary->video('my-video')
    ->resize(Resize::fill(640, 360))
    ->delivery(Delivery::format(Format::auto()))
    ->delivery(Delivery::quality(Quality::auto()));

echo $url, PHP_EOL;
```

Output:

```
https://res.cloudinary.com/<cloud_name>/video/upload/c_fill,h_360,w_640/f_auto/q_auto/my-video?_a=BAAHWXGY
```

Runnable version: [`examples/transform-and-deliver-video.php`](../examples/transform-and-deliver-video.php).

The trailing `?_a=` is anonymous SDK-version telemetry, present on every generated URL.
See [Transform and deliver an image](transform-and-deliver-image.md#the-_a-suffix) for
how to disable it. The path examples below omit it for readability.

## Result fields to keep

`$cloudinary->video()` builds a string, like the image builder. Cast explicitly outside
string context:

```php
$url = (string) $cloudinary->video('my-video')->resize(Resize::scale(480));
```

## Videos need `resource_type => 'video'` on the way in

The URL builder knows it is addressing a video because you called `->video()`. The
**upload** does not infer it — see [Upload a large video](upload-large-video.md).
Uploading a video without `resource_type` fails with `BadRequest: Invalid image file`.

## Editing the timeline

```php
use Cloudinary\Transformation\VideoEdit;

// First five seconds.
$cloudinary->video('my-video')->videoEdit(VideoEdit::trim()->startOffset(0)->endOffset(5));
// -> /video/upload/eo_5,so_0/my-video

// Quieter audio.
$cloudinary->video('my-video')->videoEdit(VideoEdit::volume(-20));
// -> /video/upload/e_volume:-20/my-video

// Auto-generated highlight preview.
$cloudinary->video('my-video')->videoEdit(VideoEdit::preview(5));
// -> /video/upload/e_preview:duration_5/my-video
```

## Generating a `<video>` tag

```php
$tag = $cloudinary->videoTag('my-video')->resize(Resize::fill(640, 360));

echo $tag, PHP_EOL;
```

The tag includes a `poster` attribute pointing at a generated JPEG still, and source
elements for the transcoded formats.

## Upload response fields specific to video

Beyond the standard image fields, a video upload returns `duration` (seconds, as a
float), plus `width` and `height` of the source. `format` is the container — `mp4` for a
typical upload.

## Troubleshooting

| Symptom | Cause |
|---|---|
| `BadRequest: Invalid image file` on upload | Missing `resource_type => 'video'`. |
| URL 404s but the image equivalent works | The asset is stored under `video/upload`; build it with `->video()`, not `->image()`. |
| Playback stalls on slow connections | Add `Delivery::quality(Quality::auto())`, and consider adaptive streaming. |
| `<video>` tag has no poster | The poster still is generated on first request; allow a moment after upload. |

## Related

- [Upload a large video](upload-large-video.md)
- [Transform and deliver an image](transform-and-deliver-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
