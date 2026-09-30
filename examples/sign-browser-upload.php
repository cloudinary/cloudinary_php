<?php

/**
 * Sign upload parameters so a browser can upload directly to Cloudinary.
 *
 * The api_secret never leaves the server: it signs the parameters here, and the client
 * posts the file plus that signature to the Cloudinary upload endpoint.
 *
 * This file prints what a signing endpoint would return, then proves the signature is
 * valid by performing the upload the browser would perform.
 *
 * Prerequisites:
 *   composer require cloudinary/cloudinary_php
 *   export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *
 * Run:
 *   php examples/sign-browser-upload.php
 *
 * In your own project, the import and instantiation are:
 *   require 'vendor/autoload.php';
 *   use Cloudinary\Cloudinary;
 *   $cloudinary = new Cloudinary();
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Api\ApiUtils;
use Cloudinary\Cloudinary;

const UPLOAD_FOLDER = 'examples/browser-uploads';
const SAMPLE_FILE   = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';

function main(): void
{
    $cloudinary = new Cloudinary();
    $cloud      = $cloudinary->configuration->cloud;

    // Only the parameters the client may send. Each one is covered by the signature.
    $params = [
        'timestamp' => time(),
        'folder'    => UPLOAD_FOLDER,
    ];

    $signature = ApiUtils::signParameters($params, $cloud->apiSecret);

    // What the signing endpoint hands to the browser. Note: no api_secret.
    $payload = $params + [
        'api_key'    => $cloud->apiKey,
        'cloud_name' => $cloud->cloudName,
        'signature'  => $signature,
    ];

    echo 'Signing endpoint would return:', PHP_EOL;
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL, PHP_EOL;

    // Perform the upload exactly as the browser would, to prove the signature validates.
    $result = uploadAsBrowser($payload);

    echo 'Browser-side upload accepted:', PHP_EOL;
    echo '  public_id:  ', $result['public_id'], PHP_EOL;
    echo '  folder:     ', $result['folder'], PHP_EOL;
    echo '  secure_url: ', $result['secure_url'], PHP_EOL;
}

/**
 * Posts to the upload endpoint with the signed fields, the way browser JavaScript would.
 *
 * @param array<string, mixed> $payload
 *
 * @return array<string, mixed>
 */
function uploadAsBrowser(array $payload): array
{
    $endpoint = sprintf('https://api.cloudinary.com/v1_1/%s/image/upload', $payload['cloud_name']);

    $fields = [
        'file'      => SAMPLE_FILE,
        'api_key'   => $payload['api_key'],
        'timestamp' => $payload['timestamp'],
        'folder'    => $payload['folder'],
        'signature' => $payload['signature'],
    ];

    $curl = curl_init($endpoint);
    curl_setopt_array($curl, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($fields),
        CURLOPT_RETURNTRANSFER => true,
    ]);

    $body = curl_exec($curl);
    if ($body === false) {
        throw new RuntimeException('Upload request failed: ' . curl_error($curl));
    }

    $decoded = json_decode((string) $body, true);
    if (isset($decoded['error'])) {
        throw new RuntimeException($decoded['error']['message']);
    }

    return $decoded;
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, 'Signing failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check that CLOUDINARY_URL is set (Console > Settings > API Keys).' . PHP_EOL);
    exit(1);
}
