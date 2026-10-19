<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\HttpClient;

use Cloudinary\HttpClient\Psr18Handler;
use Cloudinary\Test\CloudinaryTestCase;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

/**
 * Covers the Guzzle handler that forwards requests to a PSR-18 client.
 */
final class Psr18HandlerTest extends CloudinaryTestCase
{
    /**
     * Should pass the request to the client unchanged and fulfill with its response.
     */
    public function testForwardsRequestAndFulfillsWithResponse()
    {
        $request  = new Request('POST', 'https://api.cloudinary.com/v1_1/test123/image/upload', [], 'body');
        $response = new Response(201, ['X-Test' => 'yes'], '{"ok":true}');
        $client   = self::client(static fn() => $response);

        $promise = (new Psr18Handler($client))($request, ['timeout' => 5, 'proxy' => 'http://proxy:8080']);

        self::assertSame(PromiseInterface::FULFILLED, $promise->getState());
        self::assertSame($response, $promise->wait());
        self::assertSame([$request], $client->requests);
    }

    /**
     * Should return error responses as fulfilled promises, leaving status handling to the SDK.
     */
    public function testFulfillsWithErrorResponses()
    {
        $response = new Response(500, [], '{"error":{"message":"boom"}}');

        $promise = (new Psr18Handler(self::client(static fn() => $response)))(self::request(), []);

        self::assertSame($response, $promise->wait());
    }

    /**
     * Should reject PSR-18 network errors with a Guzzle ConnectException.
     */
    public function testNetworkErrorBecomesConnectException()
    {
        $request = self::request();
        $error   = new class ('Could not resolve host') extends RuntimeException implements NetworkExceptionInterface {
            public function getRequest(): RequestInterface
            {
                return new Request('GET', 'https://example.com');
            }
        };

        $rejection = self::rejectionOf((new Psr18Handler(self::client(static fn() => throw $error)))($request, []));

        self::assertInstanceOf(ConnectException::class, $rejection);
        self::assertSame('Could not resolve host', $rejection->getMessage());
        self::assertSame($request, $rejection->getRequest());
        self::assertSame($error, $rejection->getPrevious());
    }

    /**
     * Should reject other PSR-18 client errors with a Guzzle RequestException.
     */
    public function testClientErrorBecomesRequestException()
    {
        $request = self::request();
        $error   = new class ('Invalid request') extends RuntimeException implements ClientExceptionInterface {
        };

        $rejection = self::rejectionOf((new Psr18Handler(self::client(static fn() => throw $error)))($request, []));

        self::assertInstanceOf(RequestException::class, $rejection);
        self::assertNotInstanceOf(ConnectException::class, $rejection);
        self::assertSame($request, $rejection->getRequest());
        self::assertSame($error, $rejection->getPrevious());
    }

    /**
     * Should pass Guzzle exceptions through unchanged when Guzzle is the PSR-18 client.
     */
    public function testGuzzleExceptionPassesThrough()
    {
        $error = new ConnectException('Connection refused', self::request());

        $handler   = new Psr18Handler(self::client(static fn() => throw $error));
        $rejection = self::rejectionOf($handler(self::request(), []));

        self::assertSame($error, $rejection);
    }

    /**
     * Should not catch exceptions outside the PSR-18 contract.
     */
    public function testUnrelatedExceptionIsNotCaught()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('bug in client');

        $handler = new Psr18Handler(self::client(static fn() => throw new RuntimeException('bug in client')));
        $handler(self::request(), []);
    }

    /**
     * Returns a PSR-18 client that records requests and answers with the given callback.
     */
    private static function client(callable $respond)
    {
        return new class ($respond) implements ClientInterface {
            public array $requests = [];

            private $respond;

            public function __construct(callable $respond)
            {
                $this->respond = $respond;
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->requests[] = $request;

                return ($this->respond)($request);
            }
        };
    }

    /**
     * Returns a sample request.
     */
    private static function request()
    {
        return new Request('GET', 'https://api.cloudinary.com/v1_1/test123/ping');
    }

    /**
     * Returns the reason a promise was rejected with.
     */
    private static function rejectionOf(PromiseInterface $promise)
    {
        self::assertSame(PromiseInterface::REJECTED, $promise->getState());

        try {
            $promise->wait();
        } catch (Throwable $e) {
            return $e;
        }

        self::fail('Promise was not rejected');
    }
}
