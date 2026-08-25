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

## What `pending` means depends on your product environment

`pending` is a moderation state, not a guaranteed access state. Whether a pending asset is
publicly deliverable is governed by a product-environment setting, so it differs between
accounts — on many it **is** deliverable while awaiting review, which surprises people.

Do not rely on moderation as an access-control mechanism in either direction. Verify the
behaviour on your own environment, and if content must not be reachable before review,
enforce that explicitly: upload as
[`'type' => 'private'`](https://cloudinary.com/documentation/upload_images.md) or into a
restricted folder, then publish after approval. The same applies to what the asset looks
like in the Media Library versus on the CDN — those are separate surfaces.

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

How each state maps to delivery is again environment-configurable — commonly `rejected`
is taken out of delivery and `approved` stays, but confirm it on your environment rather
than assuming it.

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
perceptual duplicate detection — must be enabled on your account first, which the account
owner does in the Console; some add-ons also require accepting the provider's terms of
service before the first call will succeed. An agent cannot do either step: if the call
fails for this reason, tell the user what to enable rather than retrying.

The available kinds and their provider-specific response shapes are listed in
[moderation add-ons](https://cloudinary.com/documentation/moderation_addons.md).

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
- [Moderation add-ons](https://cloudinary.com/documentation/moderation_addons.md) — the
  kinds beyond `manual`, and what each returns.
