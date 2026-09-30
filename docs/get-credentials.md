# Get Cloudinary credentials

## When to use

Before any API call. Every request needs a cloud name, API key, and API secret.

## Fastest route: provision a cloud, no signup

If you do not already have credentials, provision a temporary cloud from the terminal:

```bash
npx @cloudinary/cloud
```

The command prints a `CLOUDINARY_URL` and a `claim_url`.

**If you are an agent running this for someone:** show the user the `claim_url`. It is
the only way they can attach the cloud to a real account and keep it — it expires, and
the assets go with it. Do not bury it in a log.

## Complete flow

Export the value the command printed:

```bash
export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
```

Confirm it reaches the SDK:

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary();

echo 'Cloud: ', $cloudinary->configuration->cloud->cloudName, PHP_EOL;
echo 'Reachable: ', $cloudinary->adminApi()->ping()['status'], PHP_EOL;
```

Expected output:

```
Cloud: your-cloud-name
Reachable: ok
```

## Alternative: an existing account

Copy the API environment variable from
[Console > Settings > API Keys](https://console.cloudinary.com/settings/api-keys). It is
already in `CLOUDINARY_URL` form. To create an account, see
[Cloudinary registration](https://cloudinary.com/users/register_free).

## Result fields to keep

| Field | Purpose |
|---|---|
| `cloud_name` | Identifies your cloud; appears in every delivery URL. Not a secret. |
| `api_key` | Identifies the calling application. Not a secret. |
| `api_secret` | **Secret.** Signs requests. Server-side only — never ship it to a browser or mobile app. |

## Keep the secret out of your code

Read credentials from the environment, not from source. The SDK does this by default
when you call `new Cloudinary()` with no arguments.

If you commit an `api_secret` by accident, rotate it in
[Console > Settings > API Keys](https://console.cloudinary.com/settings/api-keys);
removing the commit is not enough.

## Troubleshooting

| Symptom | Fix |
|---|---|
| `ConfigurationException: Invalid configuration, please set up your environment` | `CLOUDINARY_URL` is unset or malformed. It must be `cloudinary://key:secret@cloud_name`. |
| `AuthorizationRequired` on every call | Key and secret do not match the cloud, or the secret was rotated. |
| Works in your shell, fails in the app | The web server or container does not inherit your shell environment; set the variable where the process actually runs. |

## Related

- [Configure Cloudinary](configure-cloudinary.md)
- [Import and call the SDK](import-and-call.md)
- [Troubleshoot errors](troubleshoot-errors.md)
