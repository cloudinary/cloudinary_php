<?php

/**
 * The correct way to import, instantiate, and call this SDK.
 *
 * The API accessors are methods, not properties: uploadApi(), adminApi(), searchApi().
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/import-and-call.php
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

function main(): void
{
    $cloudinary = new Cloudinary();

    // Correct: uploadApi() with parentheses.
    // Writing $cloudinary->uploadApi->upload(...) is a fatal error.
    $result = $cloudinary->uploadApi()->upload(SOURCE_IMAGE, [
        'public_id' => 'examples/import-and-call',
    ]);

    echo 'Uploaded: ', $result['public_id'], PHP_EOL;

    // Responses are Cloudinary\Api\ApiResponse, which extends ArrayObject.
    echo 'Response class: ', get_class($result), PHP_EOL;
    echo 'Field access:   ', $result['secure_url'], PHP_EOL;
    echo 'As plain array: ', count($result->getArrayCopy()), ' keys', PHP_EOL;

    // The three API entry points.
    echo 'uploadApi(): ', get_class($cloudinary->uploadApi()), PHP_EOL;
    echo 'adminApi():  ', get_class($cloudinary->adminApi()), PHP_EOL;
    echo 'searchApi(): ', get_class($cloudinary->searchApi()), PHP_EOL;

    // URL and tag builders hang off the instance directly.
    echo 'image():     ', $cloudinary->image('examples/import-and-call'), PHP_EOL;
    echo 'imageTag():  ', $cloudinary->imageTag('examples/import-and-call'), PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Call failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
