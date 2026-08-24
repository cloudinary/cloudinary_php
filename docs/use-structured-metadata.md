# Use structured metadata

## When to use

Attaching typed, validated fields to assets — photographer, campaign, expiry date,
category — where free-form tags and context are too loose.

Structured metadata differs from `context`: fields are declared once on the account, have
a type, and can be mandatory or validated. Undefined keys are rejected rather than
silently stored.

## Complete flow

Declare a field, then set values on assets.

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

// 1. Declare the field once for the account.
$field = new StringMetadataField('Photographer');
$field->setExternalId('photographer');

$cloudinary->adminApi()->addMetadataField($field);

// 2. Set a value at upload time.
$result = $cloudinary->uploadApi()->upload(
    'https://res.cloudinary.com/demo/image/upload/sample.jpg',
    [
        'public_id' => 'docs/metadata-demo',
        'metadata'  => ['photographer' => 'Ada Lovelace'],
    ]
);

echo json_encode($result['metadata']), PHP_EOL;
// {"photographer":"Ada Lovelace"}
```

Runnable version: [`examples/use-structured-metadata.php`](../examples/use-structured-metadata.php).

## Result fields to keep

`addMetadataField()` returns the field definition:

| Field | Meaning |
|---|---|
| `external_id` | The key you use in `metadata` maps. Set it explicitly. |
| `type` | `string`, `integer`, `date`, `enum`, or `set`. |
| `label` | Human-readable name shown in the Media Library. |
| `mandatory` | Whether uploads must supply it. |
| `default_value` | Applied when no value is given. |
| `validation` | Constraint rules, if any. |

## Set the external ID yourself

Without `setExternalId()`, Cloudinary generates one, and your code has no stable key to
write against. Set it explicitly and treat it as the field's permanent name.

## Field types

```php
use Cloudinary\Api\Metadata\DateMetadataField;
use Cloudinary\Api\Metadata\EnumMetadataField;
use Cloudinary\Api\Metadata\IntMetadataField;
use Cloudinary\Api\Metadata\SetMetadataField;
use Cloudinary\Api\Metadata\StringMetadataField;

$campaign = new StringMetadataField('Campaign');
$priority = new IntMetadataField('Priority');
$expires  = new DateMetadataField('Expires on');
```

`EnumMetadataField` and `SetMetadataField` take a datasource of allowed values — single
choice and multiple choice respectively.

## Undefined keys are rejected

Metadata keys must exist before use:

```php
$cloudinary->uploadApi()->upload($file, [
    'metadata' => ['nonexistent_field' => 'x'],
]);
// BadRequest: Metadata External IDs do not exist: ["nonexistent_field"]
```

This is deliberate — a typo fails loudly rather than writing a field nobody reads.
Declare fields at deploy time, not per upload.

## Updating values on existing assets

```php
$cloudinary->adminApi()->update('docs/metadata-demo', [
    'metadata' => ['photographer' => 'Grace Hopper'],
]);
```

## Listing and removing fields

```php
$fields = $cloudinary->adminApi()->listMetadataFields();

foreach ($fields['metadata_fields'] as $field) {
    echo $field['external_id'], ' (', $field['type'], ')', PHP_EOL;
}

$cloudinary->adminApi()->deleteMetadataField('photographer');
```

Deleting a field removes it from every asset. There is no undo.

## Searching by metadata

Metadata is indexed and searchable:

```php
$cloudinary->searchApi()
    ->expression('metadata.photographer:"Ada Lovelace"')
    ->execute();
```

## Troubleshooting

| Symptom | Cause |
|---|---|
| `BadRequest: Metadata External IDs do not exist` | The field was never declared, or the key is misspelled. |
| `BadRequest: external id <name> already exists` | The field is already declared. Declaring is not idempotent — catch this if your deploy step may run twice. |
| Value silently absent | You passed `context` where you meant `metadata`; they are different systems. |
| Mandatory-field error on upload | A field is marked mandatory; supply it or clear the flag. |

## Related

- [Search and manage assets](search-and-manage-assets.md)
- [Upload an image](upload-image.md)
- [Troubleshoot errors](troubleshoot-errors.md)
