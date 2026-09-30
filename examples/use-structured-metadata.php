<?php

/**
 * Declare a structured metadata field, set it on an upload, update it, and search by it.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/use-structured-metadata.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Cloudinary;

const SOURCE_IMAGE = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';
const PUBLIC_ID    = 'examples/metadata-demo';
const FIELD_ID     = 'photographer';

function main(): void
{
    $cloudinary = new Cloudinary();

    declareField($cloudinary);

    // Set the value at upload time. Keys must already exist, or the upload is rejected.
    $result = $cloudinary->uploadApi()->upload(SOURCE_IMAGE, [
        'public_id' => PUBLIC_ID,
        'metadata'  => [FIELD_ID => 'Ada Lovelace'],
    ]);

    echo 'Uploaded: ', $result['public_id'], PHP_EOL;
    echo 'Metadata: ', json_encode($result['metadata']), PHP_EOL;

    // Change the value on the existing asset.
    $updated = $cloudinary->adminApi()->update(PUBLIC_ID, [
        'metadata' => [FIELD_ID => 'Grace Hopper'],
    ]);

    echo 'Updated:  ', json_encode($updated['metadata']), PHP_EOL;

    // Declared fields are indexed and searchable.
    sleep(5);

    $found = $cloudinary->searchApi()
        ->expression('metadata.' . FIELD_ID . ':"Grace Hopper"')
        ->execute();

    echo 'Search matches: ', $found['total_count'], PHP_EOL;

    // What the account has declared.
    $fields = $cloudinary->adminApi()->listMetadataFields();

    foreach ($fields['metadata_fields'] as $field) {
        echo '  ', $field['external_id'], ' (', $field['type'], ')', PHP_EOL;
    }
}

/**
 * Declares the field, tolerating the case where a previous run already created it.
 */
function declareField(Cloudinary $cloudinary): void
{
    $field = new StringMetadataField('Photographer');
    $field->setExternalId(FIELD_ID);

    try {
        $cloudinary->adminApi()->addMetadataField($field);
        echo 'Declared field: ', FIELD_ID, PHP_EOL;
    } catch (BadRequest $e) {
        // Declaring is not idempotent; an existing external_id is a BadRequest.
        if (!str_contains($e->getMessage(), 'already exists')) {
            throw $e;
        }

        echo 'Field already declared: ', FIELD_ID, PHP_EOL;
    }
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Structured metadata flow failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
