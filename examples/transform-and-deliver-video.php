<?php

/**
 * Build video delivery URLs, edit the timeline, and generate a <video> tag.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *   php examples/upload-large-video.php   (creates the asset this file transforms)
 *
 * Run:
 *   php examples/transform-and-deliver-video.php
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
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\VideoEdit;

const PUBLIC_ID = 'examples/upload-large-video';

function main(): void
{
    $cloudinary = new Cloudinary();

    // Resize with auto format and quality.
    $optimized = $cloudinary->video(PUBLIC_ID)
        ->resize(Resize::fill(640, 360))
        ->delivery(Delivery::format(Format::auto()))
        ->delivery(Delivery::quality(Quality::auto()));

    echo 'Optimized: ', $optimized, PHP_EOL;

    // Trim to the first five seconds.
    $trimmed = $cloudinary->video(PUBLIC_ID)
        ->videoEdit(VideoEdit::trim()->startOffset(0)->endOffset(5));

    echo 'Trimmed:   ', $trimmed, PHP_EOL;

    // Auto-generated highlight preview.
    echo 'Preview:   ', $cloudinary->video(PUBLIC_ID)->videoEdit(VideoEdit::preview(5)), PHP_EOL;

    // The <video> tag carries a generated poster image.
    $tag = $cloudinary->videoTag(PUBLIC_ID)->resize(Resize::fill(640, 360));

    echo 'Tag:       ', $tag, PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Building video URLs failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
