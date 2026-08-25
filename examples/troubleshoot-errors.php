<?php

/**
 * Trigger the common Cloudinary errors on purpose and handle each one by type.
 *
 * Every failure here is deliberate. The point is to show which exception a given mistake
 * produces, so real error handling can be written against the right type.
 *
 * SDK logging is disabled so each failure prints once rather than being preceded by
 * CRITICAL log lines on stderr.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/troubleshoot-errors.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Exception\ConfigurationException;

function main(): void
{
    // Quiet the SDK's own logging so deliberate failures do not print twice.
    //
    // Build the configuration from the environment first, then override. Passing a
    // partial array to the constructor REPLACES the configuration rather than merging
    // with it, so `new Cloudinary(['logging' => ...])` would discard the credentials.
    // getenv() returns false when unset, and fromCloudinaryUrl() requires a string, so
    // coerce to '' to get the SDK's own ConfigurationException rather than a TypeError.
    $configuration = Configuration::fromCloudinaryUrl(getenv('CLOUDINARY_URL') ?: '');
    $configuration->logging->enabled = false;

    $cloudinary = new Cloudinary($configuration);

    echo 'Connectivity: ', $cloudinary->adminApi()->ping()['status'], PHP_EOL, PHP_EOL;

    report('Missing credentials', static function (): void {
        // ConfigurationException is thrown by the constructor and does NOT extend
        // ApiError, so it needs its own catch block.
        new Cloudinary('cloudinary://');
    });

    report('Video uploaded as an image', static function () use ($cloudinary): void {
        // Without resource_type => 'video' this fails: upload() defaults to 'image'.
        $cloudinary->uploadApi()->upload('https://res.cloudinary.com/demo/video/upload/dog.mp4');
    });

    report('Asset that does not exist', static function () use ($cloudinary): void {
        $cloudinary->adminApi()->asset('examples/definitely-not-here-abcdef0123456789');
    });

    report('Search with a leading wildcard', static function () use ($cloudinary): void {
        // A '*' may end a term but not begin one.
        $cloudinary->searchApi()->expression('tags:*bag*')->execute();
    });

    report('Undeclared metadata field', static function () use ($cloudinary): void {
        $cloudinary->uploadApi()->upload('https://res.cloudinary.com/demo/image/upload/sample.jpg', [
            'metadata' => ['undeclared_field_abcdef' => 'value'],
        ]);
    });
}

/**
 * Runs a deliberately failing operation and reports which exception type came back.
 */
function report(string $label, callable $operation): void
{
    echo $label, PHP_EOL;

    try {
        $operation();
        echo '  unexpectedly succeeded', PHP_EOL, PHP_EOL;

        return;
    } catch (ConfigurationException $e) {
        $type = 'ConfigurationException (not an ApiError)';
    } catch (ApiError $e) {
        $type = basename(str_replace('\\', '/', get_class($e)));
    }

    echo '  type:    ', $type, PHP_EOL;
    echo '  message: ', firstLine($e->getMessage()), PHP_EOL, PHP_EOL;
}

function firstLine(string $message): string
{
    $line = strtok($message, "\n");
    $line = $line === false ? $message : $line;

    return strlen($line) > 120 ? substr($line, 0, 117) . '...' : $line;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Troubleshooting walkthrough failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
