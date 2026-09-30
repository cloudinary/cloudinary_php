<?php

/**
 * Search for assets by expression, then tag, update, rename, and delete one.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/search-and-manage-assets.php
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
const PUBLIC_ID    = 'examples/search-demo';
const RENAMED_ID   = 'examples/search-demo-renamed';
const TAG          = 'example-catalog';

function main(): void
{
    $cloudinary = new Cloudinary();

    // Create something to find.
    $cloudinary->uploadApi()->upload(SOURCE_IMAGE, [
        'public_id' => PUBLIC_ID,
        'tags'      => [TAG],
    ]);

    echo 'Uploaded ', PUBLIC_ID, PHP_EOL;

    // Search is indexed asynchronously, so a fresh asset takes a moment to appear.
    sleep(5);

    $results = $cloudinary->searchApi()
        ->expression('resource_type:image AND tags=' . TAG)
        ->sortBy('created_at', 'desc')
        ->maxResults(10)
        ->execute();

    echo 'Search matches: ', $results['total_count'], PHP_EOL;

    foreach ($results['resources'] as $asset) {
        echo '  ', $asset['public_id'], PHP_EOL;
    }

    // The Admin API reads through immediately, with no indexing delay.
    $asset = $cloudinary->adminApi()->asset(PUBLIC_ID);
    echo 'Direct lookup: ', $asset['public_id'], ' (asset_id ', $asset['asset_id'], ')', PHP_EOL;

    // asset_id is immutable, so it is the safer thing to store.
    $byAssetId = $cloudinary->adminApi()->assetByAssetId($asset['asset_id']);
    echo 'By asset_id:   ', $byAssetId['public_id'], PHP_EOL;

    // Routine management.
    $cloudinary->uploadApi()->addTag('seasonal', [PUBLIC_ID]);
    echo 'Tagged seasonal', PHP_EOL;

    $cloudinary->adminApi()->update(PUBLIC_ID, [
        'context' => ['alt' => 'Sample image managed by the PHP SDK example'],
    ]);
    echo 'Context updated', PHP_EOL;

    $renamed = $cloudinary->uploadApi()->rename(PUBLIC_ID, RENAMED_ID);
    echo 'Renamed to ', $renamed['public_id'], PHP_EOL;

    $destroyed = $cloudinary->uploadApi()->destroy(RENAMED_ID);
    echo 'Deleted: ', $destroyed['result'], PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Search and manage failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
