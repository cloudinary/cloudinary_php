# Troubleshoot errors

## When to use

An SDK call threw, and you want to know whether it is your code, your credentials, your
account, or the input.

## Exception types

All API failures extend `Cloudinary\Api\Exception\ApiError`:

| Exception | HTTP | Typical cause |
|---|---|---|
| `BadRequest` | 400 | Malformed input — wrong `resource_type`, bad search expression, undefined metadata key. |
| `AuthorizationRequired` | 401 | Bad or mismatched credentials; a feature not enabled for the account. |
| `NotAllowed` | 403 | The operation is forbidden on this account or asset. |
| `NotFound` | 404 | No asset with that `public_id` and `resource_type`. |
| `AlreadyExists` | 409 | The identifier is taken. |
| `RateLimited` | 420, 429 | Too many requests — or an unsubscribed add-on. |
| `GeneralError` | 500, anything unmapped | Server-side failure, or a status this SDK has no specific class for. |

Configuration problems throw from a **different namespace**:

```
Cloudinary\Exception\ConfigurationException  // not under Api\Exception
```

### Only the message is preserved

This SDK constructs exceptions with the server's error message and nothing else. There is
no status-code getter and no structured error body — `getCode()` returns `0`:

```php
try {
    $cloudinary->adminApi()->asset('missing');
} catch (ApiError $e) {
    $e->getMessage();   // 'Resource not found - missing'
    $e->getCode();      // 0 — not the HTTP status
    get_class($e);      // Cloudinary\Api\Exception\NotFound — this is the signal
}
```

Branch on the **exception class**, not on a code parsed out of the message. The class is
the only reliable machine-readable part.

### 423 and other unmapped statuses

Only the statuses in the table have their own class. Anything else — notably **423
(asset still processing)** — becomes a `GeneralError` whose message is the raw response
body rather than the parsed error text:

```
GeneralError: Server returned unexpected status code - 423 - {"error":{"message":"..."}}
```

So a 423 cannot be distinguished by type. If you need to retry on it, match the status in
the message:

```php
use Cloudinary\Api\Exception\GeneralError;

try {
    $cloudinary->adminApi()->asset('still-processing');
} catch (GeneralError $e) {
    if (str_contains($e->getMessage(), 'status code - 423')) {
        // Asset is still being processed — back off and retry.
    }

    throw $e;
}
```

Cloudinary platform status: [status.cloudinary.com](https://status.cloudinary.com).

## Catching them

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Cloudinary;
use Cloudinary\Exception\ConfigurationException;

try {
    $cloudinary = new Cloudinary();
    $asset      = $cloudinary->adminApi()->asset('might-not-exist');
} catch (ConfigurationException $e) {
    fwrite(STDERR, 'Credentials are missing or malformed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
} catch (NotFound $e) {
    fwrite(STDERR, 'No such asset.' . PHP_EOL);
    exit(1);
} catch (ApiError $e) {
    fwrite(STDERR, 'Cloudinary API error: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

Catch `ConfigurationException` separately: it is thrown by the **constructor**, before
any request, and it does not extend `ApiError`. A `catch (ApiError)` alone will miss it.

## Common failures

### `Call to a member function upload() on null`

`uploadApi` is a method. Write `$cloudinary->uploadApi()->upload(...)`.

### `Invalid configuration, please set up your environment`

No usable credentials. `CLOUDINARY_URL` must look like
`cloudinary://key:secret@cloud_name`. This throws at construction — if you see it at
startup rather than on first API call, that is why.

### `BadRequest: Invalid image file`

A video or non-image uploaded without `resource_type`:

```php
$cloudinary->uploadApi()->upload('clip.mp4', ['resource_type' => 'video']);
```

### `Invalid Signature <hash>. String to sign - '...'`

The parameters signed and the parameters sent differ. The message prints the exact string
that was signed — compare it against what the client posted. See
[Sign a browser upload](sign-browser-upload.md).

### `BadRequest: Query Error (at position N)`

A malformed search expression, most often a leading wildcard. `tags:bag*` is valid;
`tags:*bag*` is not.

### `BadRequest: Metadata External IDs do not exist`

The metadata field was never declared. See
[Use structured metadata](use-structured-metadata.md).

### `Class "Cloudinary\Api\Utils\ApiUtils" not found`

Directory paths do not always match namespaces. The class is `Cloudinary\Api\ApiUtils`.

### A just-uploaded asset is not in search results

Search is indexed asynchronously. Use `adminApi()->asset()` with the `public_id` when you
need the asset immediately after upload.

## Quieting the SDK's own logging

By default the SDK writes `CRITICAL` lines to stderr before an exception reaches your
handler, so one failure can look like three. To handle errors yourself without the noise:

```php
use Cloudinary\Configuration\Configuration;

// getenv() returns false when unset; fromCloudinaryUrl() requires a string, so `?: ''`
// yields the SDK's ConfigurationException instead of a raw PHP TypeError.
$configuration = Configuration::fromCloudinaryUrl(getenv('CLOUDINARY_URL') ?: '');
$configuration->logging->enabled = false;

$cloudinary = new Cloudinary($configuration);
```

Build from the environment and then override. Passing a partial array to the constructor
**replaces** the configuration rather than merging into it, so
`new Cloudinary(['logging' => ['enabled' => false]])` throws
`ConfigurationException` — the credentials are gone.

Note that error messages can include request parameters — the signature error prints the
full string-to-sign. Keep SDK logs out of any destination you would not trust with that.

## Checking whether it is you or the service

```php
$cloudinary->adminApi()->ping();   // ['status' => 'ok']
```

`ping()` exercises credentials and connectivity with no side effects. If it succeeds and
your call still fails, the problem is in the call, not the setup.

Current quota and usage:

```php
$usage = $cloudinary->adminApi()->usage();

echo $usage['plan'], PHP_EOL;
```

## Related

- [Get Cloudinary credentials](get-credentials.md)
- [Configure Cloudinary](configure-cloudinary.md)
- [Import and call the SDK](import-and-call.md)
