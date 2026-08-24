<?php

/**
 * Upload a video. Files above chunk_size (20 MB by default) are chunked automatically —
 * there is no separate large-upload method in this SDK.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/upload-large-video.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

const SOURCE_VIDEO = 'https://res.cloudinary.com/demo/video/upload/dog.mp4';
const PUBLIC_ID    = 'examples/upload-large-video';

function main(): void
{
    $cloudinary = new Cloudinary();

    // resource_type is required: upload() defaults to 'image' and a video without this
    // option fails with "BadRequest: Invalid image file".
    $result = $cloudinary->uploadApi()->upload(
        SOURCE_VIDEO,
        [
            'resource_type' => 'video',
            'public_id'     => PUBLIC_ID,
            'tags'          => ['example'],
        ]
    );

    echo 'Uploaded: ', $result['public_id'], PHP_EOL;
    echo 'Duration: ', $result['duration'], ' seconds', PHP_EOL;
    echo 'Size:     ', $result['width'], 'x', $result['height'], ' ', $result['format'], PHP_EOL;
    echo 'Bytes:    ', $result['bytes'], PHP_EOL;
    echo 'URL:      ', $result['secure_url'], PHP_EOL;

    // Chunking threshold currently in effect.
    echo 'Chunk size: ', $cloudinary->configuration->api->chunkSize, ' bytes', PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Video upload failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
