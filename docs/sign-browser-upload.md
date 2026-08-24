# Sign a browser upload

## When to use

Letting a browser or mobile app upload straight to Cloudinary without routing the bytes
through your server — and without ever exposing your `api_secret`.

Your server signs a short-lived set of parameters; the client posts the file plus that
signature directly to Cloudinary.

## Complete flow

Server side — generate the signature:

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Api\ApiUtils;
use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();
$cloud      = $cloudinary->configuration->cloud;

// Only the parameters the client is allowed to send. Every one of these is signed.
$params = [
    'timestamp' => time(),
    'folder'    => 'user-uploads',
];

$signature = ApiUtils::signParameters($params, $cloud->apiSecret);

header('Content-Type: application/json');
echo json_encode($params + [
    'api_key'    => $cloud->apiKey,
    'cloud_name' => $cloud->cloudName,
    'signature'  => $signature,
]);
```

Note the import: the class is `Cloudinary\Api\ApiUtils`, even though the file lives at
`src/Api/Utils/ApiUtils.php`.

Client side — post the file with those fields:

```js
const config = await fetch('/sign-upload').then((r) => r.json());

const form = new FormData();
form.append('file', fileInput.files[0]);
form.append('api_key', config.api_key);
form.append('timestamp', config.timestamp);
form.append('folder', config.folder);
form.append('signature', config.signature);

const response = await fetch(
  `https://api.cloudinary.com/v1_1/${config.cloud_name}/image/upload`,
  { method: 'POST', body: form }
);

const result = await response.json();
console.log(result.public_id, result.secure_url);
```

Runnable version of the signing half: [`examples/sign-browser-upload.php`](../examples/sign-browser-upload.php).

## Result fields to keep

| Field | Sent to the client? | Notes |
|---|---|---|
| `signature` | Yes | 40-character SHA-1 hex. Valid only for the exact signed parameters. |
| `api_key` | Yes | Public identifier, safe to expose. |
| `cloud_name` | Yes | Public, appears in every delivery URL. |
| `timestamp` | Yes | Must be sent back unchanged; the signature covers it. |
| `api_secret` | **No** | Never leaves your server. |

## Every signed parameter must be sent back verbatim

The server validates the signature against the parameters it receives. If the client
adds, drops, or edits any signed value, the upload is rejected with
`Invalid Signature`. To let the client choose something — a tag, say — include it in the
signed set on the server.

The error message names the exact string that was signed, which makes mismatches quick
to diagnose:

```
Invalid Signature <hash>. String to sign - 'folder=user-uploads&timestamp=1787586403'.
```

## Signatures are short-lived

`timestamp` is part of the signature and Cloudinary rejects stale ones (about an hour).
Generate a signature per upload attempt; do not cache or reuse them.

## Alternative: unsigned uploads

An [unsigned upload preset](https://cloudinary.com/documentation/upload_presets.md)
allows uploads with no signature at all, constrained by rules you configure on the
preset:

```php
$cloudinary->uploadApi()->unsignedUpload($file, 'my_unsigned_preset');
```

Anyone who finds the preset name can upload to it, so restrict it — folder, allowed
formats, size caps, moderation — and prefer signed uploads when you can run server code.

## Troubleshooting

| Symptom | Cause |
|---|---|
| `Invalid Signature` | A signed parameter differs between signing and upload. Compare against the "String to sign" in the error. |
| `Invalid Signature` only sometimes | Stale `timestamp`, or a load-balanced server with clock drift. |
| `AuthorizationRequired` | Wrong `api_key` for the cloud, or the secret was rotated. |
| Upload works but ignores `folder` | `folder` was sent but not signed, or vice versa. |

## Related

- [Upload an image](upload-image.md)
- [Get Cloudinary credentials](get-credentials.md)
- [Troubleshoot errors](troubleshoot-errors.md)
