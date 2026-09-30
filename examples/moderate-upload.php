<?php

/**
 * Queue an upload for manual moderation, list the pending queue, and approve it.
 *
 * Uses 'manual' moderation, which needs no add-on subscription. Other kinds exist —
 * AI visual moderation, perceptual duplicate detection, and other provider-backed
 * checks — but each must be enabled on the account first, and some require accepting the
 * provider's terms of service. See:
 *   https://cloudinary.com/documentation/moderation_addons.md
 *
 * Whether a 'pending' asset is publicly deliverable is a product-environment setting, so
 * do not treat moderation as access control. See docs/moderate-upload.md.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/moderate-upload.php
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
const PUBLIC_ID    = 'examples/needs-review';

function main(): void
{
    $cloudinary = new Cloudinary();

    $result = $cloudinary->uploadApi()->upload(SOURCE_IMAGE, [
        'public_id'  => PUBLIC_ID,
        'moderation' => 'manual',
    ]);

    // The upload response carries a "moderation" array and no "moderation_status" key.
    echo 'Uploaded:   ', $result['public_id'], PHP_EOL;
    echo 'Moderation: ', json_encode($result['moderation']), PHP_EOL;
    echo 'Status:     ', $result['moderation'][0]['status'], PHP_EOL;

    // The Admin API exposes both shapes.
    $asset = $cloudinary->adminApi()->asset(PUBLIC_ID);
    echo 'Via Admin API, moderation_status: ', $asset['moderation_status'], PHP_EOL;

    // Everything currently awaiting manual review.
    $pending = $cloudinary->adminApi()->assetsByModeration('manual', 'pending', [
        'max_results' => 20,
    ]);

    echo 'Pending queue: ', count($pending['resources']), PHP_EOL;

    // Approve it. Use 'rejected' to take it out of delivery instead.
    $approved = $cloudinary->adminApi()->update(PUBLIC_ID, [
        'moderation_status' => 'approved',
    ]);

    echo 'New status: ', $approved['moderation_status'], PHP_EOL;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Moderation flow failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
