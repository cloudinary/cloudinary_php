<?php

/**
 * The three ways to configure the SDK, and how to read back what it resolved.
 *
 * Only the environment-variable form performs a real API call; the others use
 * placeholder credentials to show the shape without needing extra accounts.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/configure-cloudinary.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

function main(): void
{
    // 1. From CLOUDINARY_URL. Preferred: the secret stays out of source.
    $fromEnv = new Cloudinary();

    echo 'From environment:', PHP_EOL;
    echo '  cloud_name: ', $fromEnv->configuration->cloud->cloudName, PHP_EOL;
    echo '  secure:     ', var_export($fromEnv->configuration->url->secure, true), PHP_EOL;
    echo '  chunk_size: ', $fromEnv->configuration->api->chunkSize, PHP_EOL;
    echo '  reachable:  ', $fromEnv->adminApi()->ping()['status'], PHP_EOL;

    // 2. From a CLOUDINARY_URL-style string.
    $fromString = new Cloudinary('cloudinary://my_key:my_secret@my_cloud');

    echo 'From string:', PHP_EOL;
    echo '  cloud_name: ', $fromString->configuration->cloud->cloudName, PHP_EOL;

    // 3. From an array — for credentials loaded from a config store or secret manager.
    $fromArray = new Cloudinary([
        'cloud' => [
            'cloud_name' => 'array_cloud',
            'api_key'    => 'my_key',
            'api_secret' => 'my_secret',
        ],
        'url' => [
            'secure_distribution' => 'cdn.example.com',
        ],
        'api' => [
            'chunk_size' => 6000000,
            'timeout'    => 120,
        ],
    ]);

    echo 'From array:', PHP_EOL;
    echo '  cloud_name: ', $fromArray->configuration->cloud->cloudName, PHP_EOL;
    echo '  chunk_size: ', $fromArray->configuration->api->chunkSize, PHP_EOL;
    echo '  custom CDN: ', $fromArray->image('sample'), PHP_EOL;

    // Configuration is per instance, so several clouds coexist in one process.
    echo 'Independent instances: ',
        $fromEnv->configuration->cloud->cloudName,
        ' / ',
        $fromArray->configuration->cloud->cloudName,
        PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Configuration failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
