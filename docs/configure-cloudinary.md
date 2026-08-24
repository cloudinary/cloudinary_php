# Configure Cloudinary

## When to use

Whenever you need credentials somewhere other than `CLOUDINARY_URL`, or you need to
change delivery defaults such as CDN hostname or upload chunk size.

## Complete flow

The default: read `CLOUDINARY_URL` from the environment.

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

echo $cloudinary->configuration->cloud->cloudName, PHP_EOL;
```

## Other configuration sources

All three forms produce an equivalent instance:

```php
// 1. Environment variable (preferred — keeps the secret out of source).
$cloudinary = new Cloudinary();

// 2. A CLOUDINARY_URL-style string.
$cloudinary = new Cloudinary('cloudinary://my_key:my_secret@my_cloud');

// 3. An array, for credentials from a config file or secret manager.
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'my_cloud',
        'api_key'    => 'my_key',
        'api_secret' => 'my_secret',
    ],
]);
```

Use form 3 when your framework already loads secrets — read them from that store and pass
them in, rather than writing literals into source.

## Delivery and API options

```php
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'my_cloud',
        'api_key'    => 'my_key',
        'api_secret' => 'my_secret',
    ],
    'url' => [
        'secure'    => true,        // https — this is already the default
        'cname'     => 'cdn.example.com',
        'secure_distribution' => 'cdn.example.com',
    ],
    'api' => [
        'chunk_size' => 20000000,   // bytes per chunk for large uploads
        'timeout'    => 60,
    ],
]);
```

## Result fields to keep

Read back what the SDK actually resolved:

```php
$cloudinary->configuration->cloud->cloudName;   // string
$cloudinary->configuration->cloud->apiKey;      // string
$cloudinary->configuration->url->secure;        // bool, default true
$cloudinary->configuration->api->chunkSize;     // int, default 20000000
```

Note the case change: configuration **input** keys are `snake_case`
(`cloud_name`, `chunk_size`), while the **properties** you read back are `camelCase`
(`cloudName`, `chunkSize`).

## A config array replaces, it does not merge

Whatever you pass to the constructor becomes the entire configuration. A partial array
does **not** layer on top of `CLOUDINARY_URL`:

```php
// Throws ConfigurationException — no credentials in this array.
$cloudinary = new Cloudinary(['logging' => ['enabled' => false]]);
```

To adjust one setting while keeping environment credentials, build a `Configuration`
first and override the property:

```php
use Cloudinary\Configuration\Configuration;

$configuration = Configuration::fromCloudinaryUrl(getenv('CLOUDINARY_URL'));
$configuration->logging->enabled = false;

$cloudinary = new Cloudinary($configuration);
```

## Per-instance configuration

Configuration belongs to the instance, so two clouds can coexist in one process:

```php
$primary = new Cloudinary();
$archive = new Cloudinary('cloudinary://key:secret@archive_cloud');

$primary->uploadApi()->upload($file);   // goes to the primary cloud
$archive->uploadApi()->upload($file);   // goes to the archive cloud
```

## Troubleshooting

| Symptom | Cause |
|---|---|
| `ConfigurationException: Invalid configuration, please set up your environment` | No credentials found. The constructor validates immediately, so this throws at construction, not at first API call. |
| URLs use `res.cloudinary.com` despite setting `cname` | `cname` applies to HTTP delivery; for HTTPS set `secure_distribution`. |
| Large uploads time out | Raise `api.timeout`, or lower `api.chunk_size` on an unreliable connection. |

## Related

- [Get Cloudinary credentials](get-credentials.md)
- [Import and call the SDK](import-and-call.md)
- [Upload a large video](upload-large-video.md)
