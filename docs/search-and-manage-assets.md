# Search and manage assets

## When to use

Finding assets by expression, and the routine management that follows — tagging,
renaming, updating metadata, deleting.

## Complete flow

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

$results = $cloudinary->searchApi()
    ->expression('resource_type:image AND tags=catalog')
    ->sortBy('created_at', 'desc')
    ->maxResults(10)
    ->execute();

echo $results['total_count'], ' matches', PHP_EOL;

foreach ($results['resources'] as $asset) {
    echo $asset['public_id'], ' ', $asset['secure_url'], PHP_EOL;
}
```

Runnable version: [`examples/search-and-manage-assets.php`](../examples/search-and-manage-assets.php).

## Result fields to keep

| Field | Meaning |
|---|---|
| `total_count` | Total matches, not just this page. |
| `resources` | Array of assets, each with `public_id`, `asset_id`, `secure_url`, `tags`, `context`. |
| `next_cursor` | Pass to `nextCursor()` for the next page. Absent on the last page. |
| `time` | Server-side query duration in ms. |

## Search expressions

| Expression | Matches |
|---|---|
| `tags=catalog` | Assets tagged `catalog` |
| `resource_type:video` | All videos |
| `folder:products/*` | Everything under `products/` |
| `bytes>1000000` | Files over 1 MB |
| `created_at>2026-01-01` | Uploaded since that date |
| `context.alt:shirt*` | Context field prefix match |

Combine with `AND`, `OR`, `NOT`.

### Leading wildcards are rejected

A `*` may end a term but not begin one:

```php
$cloudinary->searchApi()->expression('tags:bag*')->execute();   // fine
$cloudinary->searchApi()->expression('tags:*bag*')->execute();  // BadRequest: Query Error
```

To match a suffix, store a tag or context field you can prefix-match instead.

### Newly uploaded assets take a moment to appear

Search runs against an index updated shortly after upload. A just-uploaded asset may not
be found for a few seconds. Do not build a flow that uploads and immediately searches for
the same asset — use `adminApi()->asset()` with the `public_id` when you need it
immediately.

## Paging

```php
$cursor = null;

do {
    $search = $cloudinary->searchApi()->expression('tags=catalog')->maxResults(100);

    if ($cursor !== null) {
        $search->nextCursor($cursor);
    }

    $page = $search->execute();

    foreach ($page['resources'] as $asset) {
        echo $asset['public_id'], PHP_EOL;
    }

    $cursor = $page['next_cursor'] ?? null;
} while ($cursor !== null);
```

## Managing what you found

```php
// Add and remove tags in bulk.
$cloudinary->uploadApi()->addTag('seasonal', ['docs/product-shot']);
$cloudinary->uploadApi()->removeTag('seasonal', ['docs/product-shot']);

// Update context and metadata on an existing asset.
$cloudinary->adminApi()->update('docs/product-shot', [
    'context' => ['alt' => 'Blue cotton shirt'],
]);
// Reading it back, context values sit under a "custom" key:
// $asset['context'] === ['custom' => ['alt' => 'Blue cotton shirt']]

// Rename. The public_id changes; the asset_id does not.
$cloudinary->uploadApi()->rename('docs/product-shot', 'docs/product-shot-v2');

// Delete.
$cloudinary->uploadApi()->destroy('docs/product-shot-v2');
```

## Prefer `asset_id` for stored references

`public_id` changes when an asset is renamed or moved; `asset_id` never does. Store
`asset_id` and look up the current `public_id` when you need to build a URL:

```php
$asset = $cloudinary->adminApi()->assetByAssetId($assetId);

$url = $cloudinary->image($asset['public_id']);
```

Asset-ID variants exist for lookups — `assetByAssetId()`, and the by-asset-ids delete and
restore calls. URL building and the uploader methods take a `public_id`, so resolve it
first.

## Listing without searching

For simple listings, the Admin API avoids the indexing delay:

```php
$cloudinary->adminApi()->assets(['max_results' => 10]);
$cloudinary->adminApi()->assetsByTag('catalog');
$cloudinary->adminApi()->tags();
```

## Troubleshooting

| Symptom | Cause |
|---|---|
| `BadRequest: Query Error (at position N)` | Malformed expression — commonly a leading wildcard. |
| A just-uploaded asset is missing | Indexing delay; use `adminApi()->asset()` instead. |
| `total_count` exceeds `resources` length | Expected — page with `next_cursor`. |
| `NotFound` on rename or delete | Wrong `public_id`, or the asset is a different `resource_type`. |
| `AuthorizationRequired: Folder Renaming is not allowed in this cloud` | Folder renaming is not enabled for the account. |

## Related

- [Use structured metadata](use-structured-metadata.md)
- [Upload an image](upload-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
