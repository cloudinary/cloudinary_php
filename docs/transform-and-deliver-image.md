# Transform and deliver an image

## When to use

Building a delivery URL or an `<img>` tag for an image already in Cloudinary.
Transformations are applied by the CDN at delivery time — nothing is re-uploaded, and the
original is never modified.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;

$cloudinary = new Cloudinary();

$url = $cloudinary->image('sample')
    ->resize(Resize::fill(400, 400)->gravity(Gravity::auto()))
    ->delivery(Delivery::format(Format::auto()))
    ->delivery(Delivery::quality(Quality::auto()));

echo $url, PHP_EOL;
```

Output:

```
https://res.cloudinary.com/<cloud_name>/image/upload/c_fill,g_auto,h_400,w_400/f_auto/q_auto/sample
```

Runnable version: [`examples/transform-and-deliver-image.php`](../examples/transform-and-deliver-image.php).

## Result fields to keep

The builder is not a response object — it produces a string. Cast it explicitly when you
need one:

```php
$url = (string) $cloudinary->image('sample')->resize(Resize::scale(300));
```

In string context — `echo`, interpolation, concatenation — the cast is automatic.

## Always pair `f_auto` with `q_auto`

`Format::auto()` serves AVIF or WebP to browsers that accept them; `Quality::auto()`
picks a compression level per image. Together they are the single biggest byte saving
available, with no visible quality loss in most cases.

## Common transformations

These use additional classes from the same namespace:

```php
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Effect;
```

```php
// Crop to a square, keeping the most interesting region.
$cloudinary->image('sample')->resize(Resize::fill(400, 400)->gravity(Gravity::auto()));

// Scale to a width, preserving aspect ratio.
$cloudinary->image('sample')->resize(Resize::scale(300));

// Crop to a face.
$cloudinary->image('sample')->resize(Resize::thumbnail(150, 150)->gravity(Gravity::face()));

// Extend an image to a new aspect ratio with generated content.
$cloudinary->image('sample')->resize(Resize::pad(800, 800)->background(Background::generativeFill()));

// Effects and shapes chain in the order you write them.
$cloudinary->image('sample')
    ->resize(Resize::fill(200, 200))
    ->effect(Effect::grayscale());
```

Each call maps to one component of the URL, so the generated path is predictable:
`c_fill,h_200,w_200/e_grayscale/sample`.

## Generating an `<img>` tag

```php
$tag = $cloudinary->imageTag('sample')->resize(Resize::fill(400, 400));

echo $tag, PHP_EOL;
// <img src="https://res.cloudinary.com/<cloud_name>/image/upload/c_fill,h_400,w_400/sample">
```

## Delivery URLs are public

A delivery URL needs no credentials — it is meant to be put in HTML. Restricting access
is a separate feature; see
[access control](https://cloudinary.com/documentation/control_access_to_media.md).

## Nested public IDs get a `/v1/` segment

When a `public_id` contains a slash and no version is known, the SDK inserts a `v1`
placeholder:

```php
$cloudinary->image('sample');              // .../image/upload/sample
$cloudinary->image('folder/sub/sample');   // .../image/upload/v1/folder/sub/sample
```

This is expected and the URL resolves correctly. To emit a real version, pass the
`version` from the upload response.

## Troubleshooting

| Symptom | Cause |
|---|---|
| URL returns 404 | The `public_id` is wrong, or the asset is a different `resource_type`. Check `resource_type` and folder path. |
| URL contains `v1` unexpectedly | Normal for nested public IDs — see above. |
| Transformation ignored | Component order matters; verify against the generated URL rather than the code. |
| Image is larger than expected | Add `Delivery::format(Format::auto())` and `Delivery::quality(Quality::auto())`. |

## Related

- [Transform and deliver a video](transform-and-deliver-video.md)
- [Upload an image](upload-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
