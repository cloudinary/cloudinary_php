# Moderate an upload

## When to use

Reviewing user-supplied media before it becomes publicly visible — either by hand or with
an automatic moderation add-on.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

$result = $cloudinary->uploadApi()->upload(
    'https://res.cloudinary.com/demo/image/upload/sample.jpg',
    [
        'public_id'  => 'docs/needs-review',
        'moderation' => 'manual',
    ]
);

echo json_encode($result['moderation']), PHP_EOL;
// [{"kind":"manual","status":"pending"}]
```

Runnable version: [`examples/moderate-upload.php`](../examples/moderate-upload.php).

## The upload response has no `moderation_status`

This trips people up. The **upload** response carries a `moderation` array only:

```php
$result['moderation'];          // [['kind' => 'manual', 'status' => 'pending']]
$result['moderation_status'];   // not set
```

The **Admin API** returns both:

```php
$asset = $cloudinary->adminApi()->asset('docs/needs-review');

$asset['moderation'];         // [['kind' => 'manual', 'status' => 'pending']]
$asset['moderation_status'];  // 'pending'
```

Read `moderation[0]['status']` if you have an upload response; read either if you fetched
the asset.

## Result fields to keep

| Field | Meaning |
|---|---|
| `moderation[].kind` | Which moderation performed the check — `manual`, or an add-on name. |
| `moderation[].status` | `pending`, `approved`, or `rejected`. |
| `moderation_status` | Same status, flattened. Admin API responses only. |
| `public_id` | Needed to approve or reject later. |

## A pending asset is still delivered

Queuing for moderation does not hide the asset. Until you act on it, its delivery URL
works. If content must not be visible before review, upload it as
[`'type' => 'private'`](https://cloudinary.com/documentation/upload_images.md) or into a
restricted folder, and publish after approval.

## Approving and rejecting

```php
// Approve.
$cloudinary->adminApi()->update('docs/needs-review', [
    'moderation_status' => 'approved',
]);

// Reject.
$cloudinary->adminApi()->update('docs/needs-review', [
    'moderation_status' => 'rejected',
]);
```

Rejected assets are moved out of delivery; approved ones stay.

## Listing the queue

```php
$pending = $cloudinary->adminApi()->assetsByModeration('manual', 'pending', [
    'max_results' => 20,
]);

foreach ($pending['resources'] as $asset) {
    echo $asset['public_id'], PHP_EOL;
}
```

## Automatic moderation is an add-on

`'moderation' => 'manual'` needs no add-on. Automatic kinds — AI-based visual moderation,
perceptual duplicate detection — must be enabled on your account first. Without a
subscription the call fails at request time.

Because the verdict is model output, assert on the **shape** of the response — that a
status exists and is one of the expected values — not on a specific verdict for a given
image.

## Troubleshooting

| Symptom | Cause |
|---|---|
| `$result['moderation_status']` is undefined | Expected on upload responses; read `moderation[0]['status']`, or fetch via `adminApi()->asset()`. |
| `AuthorizationRequired` on an automatic kind | The moderation add-on is not enabled for the account. |
| `RateLimited` when barely sending requests | Unsubscribed add-ons can surface as rate-limit errors. |
| Asset publicly reachable while pending | By design — moderation does not gate delivery. |

## Related

- [Upload an image](upload-image.md)
- [Search and manage assets](search-and-manage-assets.md)
- [What this SDK does and does not do](platform-capabilities.md)
