# Use a custom HTTP client

## When to use

Your application already has an HTTP client with its own logging, retries, tracing,
proxy, or timeouts, and you want Cloudinary API calls to go through it. Any
[PSR-18](https://www.php-fig.org/psr/psr-18/) client works: Symfony HttpClient, Guzzle,
and others.

Without this, the SDK sends requests with its own built-in Guzzle client.

## Complete flow

Symfony HttpClient with retries:

```php
<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use GuzzleHttp\Psr7\HttpFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\RetryableHttpClient;

$httpClient = new RetryableHttpClient(HttpClient::create(['timeout' => 60]), null, 3);

$cloudinary = new Cloudinary();
$cloudinary->setHttpClient(new Psr18Client($httpClient, new HttpFactory(), new HttpFactory()));

$result = $cloudinary->uploadApi()->upload('https://res.cloudinary.com/demo/image/upload/sample.jpg');

echo $result['secure_url'], PHP_EOL;
```

`Psr18Client` needs PSR-17 factories. `GuzzleHttp\Psr7\HttpFactory` is already installed
with the SDK, so no extra package is required.

In a Symfony application, pass the `http_client` service you already configured instead
of `HttpClient::create()`, so its logging, retries and profiler integration apply.

Runnable version: [`examples/use-custom-http-client.php`](../examples/use-custom-http-client.php).

## Other PSR-18 clients

Pass any `Psr\Http\Client\ClientInterface`. For example, a Guzzle client with your own
middleware:

```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

$stack = HandlerStack::create();
// $stack->push(...your middleware...);

$cloudinary->setHttpClient(new Client(['handler' => $stack]));
```

## Where to set the client

| Call | Applies to |
|---|---|
| `$cloudinary->setHttpClient($client)` | API objects returned by `$cloudinary->uploadApi()`, `adminApi()`, `searchApi()` and `searchFoldersApi()` after the call |
| `$api->setHttpClient($client)` | That API object: `UploadApi`, `AdminApi`, `SearchApi`, `SearchFoldersApi` or `AccountApi` (Provisioning) created directly |
| `Cloudinary::setDefaultHttpClient($client)` | Every API object created after the call that has no client of its own |

An object's own client wins over the default, and the default wins over the built-in
client. Passing `null` to any of them removes that level. The setters on `Cloudinary` and
on API objects return the object, so calls can be chained:

```php
use Cloudinary\Api\Upload\UploadApi;

$uploadApi = (new UploadApi())->setHttpClient($client);

Cloudinary::setDefaultHttpClient($client); // for code that creates API objects directly
```

Use the instance setter when you inject the client through a container, or when different
clouds need different clients in one process. Use the default for code that creates API
objects with `new` and has no access to your client.

## What changes with a custom client

| Area | Built-in client | Custom PSR-18 client |
|---|---|---|
| `timeout`, `connection_timeout`, `api_proxy` config | Applied by the SDK | Ignored; configure them on your client |
| `*Async()` methods | Requests on the same API object run in parallel | Each call completes before it returns; PSR-18 has no async API |
| Network errors | `GuzzleHttp\Exception\ConnectException` | `GuzzleHttp\Exception\ConnectException` (converted from the client's exception) |
| Other client errors | `GuzzleHttp\Exception\RequestException` | `GuzzleHttp\Exception\RequestException` (converted) |
| API errors (`NotFound`, `BadRequest`, ...) | `Cloudinary\Api\Exception\*` | Unchanged |

`*Async()` methods still return Guzzle promises, so existing code keeps working.

## Security: the API secret is in every request

With `api_key` and `api_secret` configured, every request carries an
`Authorization: Basic` header that encodes your API secret. Your client sees that header.
If your client or its middleware logs request headers (debug logging, tracing, APM),
redact `Authorization` before the logs are stored. Symfony HttpClient's built-in logger
records only the method and URL.

## Troubleshooting

| Symptom | Cause |
|---|---|
| Requests still use the built-in client | The API object was created before `$cloudinary->setHttpClient()` or `Cloudinary::setDefaultHttpClient()`; get a new one, or call `setHttpClient()` on it. |
| `timeout` or `api_proxy` has no effect | They are ignored with a custom client; set them on the client. |
| `LogicException: You cannot use the "Symfony\Component\HttpClient\Psr18Client" as no PSR-17 factories have been provided` | Pass `new HttpFactory()` twice, as shown above. |
| `ConnectException` | The client could not reach the API host: DNS, proxy, or network. |
| Async calls take as long as running them one by one | Expected with a PSR-18 client. |

## Related

- [Configure Cloudinary](configure-cloudinary.md)
- [Troubleshoot errors](troubleshoot-errors.md)
- [Upload a large video](upload-large-video.md)
