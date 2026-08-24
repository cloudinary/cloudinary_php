<?php

/**
 * Verify that credentials are present and working.
 *
 * Run this first when setting up: it reports what the SDK resolved and whether the
 * account is reachable, without uploading anything.
 *
 * If you have no credentials, run `npx @cloudinary/cloud` to provision a temporary
 * cloud, then export the CLOUDINARY_URL it prints. Show the claim_url it also prints
 * to whoever owns the account — it is the only way to keep the cloud.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/get-credentials.php
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
    // Throws ConfigurationException immediately if CLOUDINARY_URL is missing or malformed.
    $cloudinary = new Cloudinary();
    $cloud      = $cloudinary->configuration->cloud;

    echo 'Cloud name: ', $cloud->cloudName, PHP_EOL;
    echo 'API key:    ', $cloud->apiKey, PHP_EOL;
    echo 'API secret: ', $cloud->apiSecret === null ? 'not set' : 'set (not printed)', PHP_EOL;

    // Exercises credentials and connectivity with no side effects.
    echo 'Ping:       ', $cloudinary->adminApi()->ping()['status'], PHP_EOL;

    $usage = $cloudinary->adminApi()->usage();

    echo 'Plan:       ', $usage['plan'], PHP_EOL;
    echo 'Credits:    ', json_encode($usage['credits'] ?? 'n/a'), PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Credential check failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Set CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>' . PHP_EOL);
    fwrite(STDERR, 'or run `npx @cloudinary/cloud` to provision a temporary cloud.' . PHP_EOL);
    exit(1);
}
