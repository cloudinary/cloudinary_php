<?php

/**
 * Upload an image to Cloudinary and print the delivery URL.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/upload-image.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

const SOURCE_IMAGE = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';
const PUBLIC_ID    = 'examples/upload-image';

function main(): void
{
    $cloudinary = new Cloudinary();

    $result = $cloudinary->uploadApi()->upload(
        SOURCE_IMAGE,
        [
            'public_id' => PUBLIC_ID,
            'tags'      => ['example'],
            'context'   => ['alt' => 'Sample image uploaded by the PHP SDK example'],
        ]
    );

    echo 'Uploaded: ', $result['public_id'], PHP_EOL;
    echo 'Asset ID: ', $result['asset_id'], PHP_EOL;
    echo 'Format:   ', $result['format'], ' ', $result['width'], 'x', $result['height'], PHP_EOL;
    echo 'URL:      ', $result['secure_url'], PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Upload failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
