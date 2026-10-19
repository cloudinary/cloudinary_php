<?php

/**
 * Send all SDK requests through your own PSR-18 HTTP client, then upload an image.
 *
 * This example uses Symfony HttpClient with retries; any PSR-18 client works.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php symfony/http-client
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/use-custom-http-client.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use GuzzleHttp\Psr7\HttpFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\RetryableHttpClient;

const SOURCE_IMAGE = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';
const PUBLIC_ID    = 'examples/use-custom-http-client';

function main(): void
{
    // Timeouts, proxy and retries are configured on your client, not in Cloudinary config.
    $httpClient = new RetryableHttpClient(HttpClient::create(['timeout' => 60]), null, 3);

    // API objects returned by this instance send their requests through it.
    $cloudinary = new Cloudinary();
    $cloudinary->setHttpClient(new Psr18Client($httpClient, new HttpFactory(), new HttpFactory()));

    $result = $cloudinary->uploadApi()->upload(
        SOURCE_IMAGE,
        [
            'public_id' => PUBLIC_ID,
            'tags'      => ['example'],
        ]
    );

    echo 'Uploaded via PSR-18 client: ', $result['public_id'], PHP_EOL;
    echo 'URL: ', $result['secure_url'], PHP_EOL;

    $asset = $cloudinary->adminApi()->asset(PUBLIC_ID);
    echo 'Fetched via PSR-18 client: ', $asset['bytes'], ' bytes', PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Request failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
