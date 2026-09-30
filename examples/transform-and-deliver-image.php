<?php

/**
 * Build optimized image delivery URLs and an <img> tag.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *   php examples/upload-image.php   (creates the asset this file transforms)
 *
 * Run:
 *   php examples/transform-and-deliver-image.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;

const PUBLIC_ID = 'examples/upload-image';

function main(): void
{
    $cloudinary = new Cloudinary();

    // Square crop, auto format and quality — the default choice for most images.
    $optimized = $cloudinary->image(PUBLIC_ID)
        ->resize(Resize::fill(400, 400)->gravity(Gravity::auto()))
        ->delivery(Delivery::format(Format::auto()))
        ->delivery(Delivery::quality(Quality::auto()));

    echo 'Optimized: ', $optimized, PHP_EOL;

    // Scale to a fixed width, preserving aspect ratio.
    echo 'Scaled:    ', $cloudinary->image(PUBLIC_ID)->resize(Resize::scale(300)), PHP_EOL;

    // Chained transformations apply in the order written.
    $chained = $cloudinary->image(PUBLIC_ID)
        ->resize(Resize::fill(200, 200))
        ->effect(Effect::grayscale());

    echo 'Chained:   ', $chained, PHP_EOL;

    // The same builder produces an HTML tag.
    $tag = $cloudinary->imageTag(PUBLIC_ID)->resize(Resize::fill(400, 400));

    echo 'Tag:       ', $tag, PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Building delivery URLs failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
