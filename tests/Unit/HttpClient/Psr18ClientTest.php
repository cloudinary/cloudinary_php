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

use Closure;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\BaseApiClient;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Api\Provisioning\AccountApi;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\Provisioning\ProvisioningConfiguration;
use Cloudinary\HttpClient;
use Cloudinary\Test\Unit\UnitTestCase;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\Utils as PromiseUtils;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;

/**
 * Sends SDK requests through PSR-18 clients (Symfony Psr18Client and Guzzle) without network access.
 */
final class Psr18ClientTest extends UnitTestCase
{
    const TEST_IMAGE_PATH = __DIR__ . '/../../assets/sample.png';
    const API_BASE_URL    = 'https://api.cloudinary.com/v1_1/' . self::CLOUD_NAME . '/';

    /**
     * @var array Requests received by the mock transport.
     */
    private $requests = [];

    public function tearDown()
    {
        Cloudinary::setDefaultHttpClient(null);

        parent::tearDown();
    }

    /**
     * Should send a signed multipart upload through the PSR-18 client.
     */
    public function testUploadThroughPsr18Client()
    {
        $this->useSymfonyClient($this->mockTransport([self::jsonResponse(['public_id' => 'sample'])]));

        $result = (new UploadApi())->upload(self::TEST_IMAGE_PATH, ['tags' => ['psr18']]);

        self::assertSame('sample', $result['public_id']);
        self::assertCount(1, $this->requests);

        $request = $this->requests[0];
        self::assertSame('POST', $request['method']);
        self::assertSame(self::API_BASE_URL . 'image/upload', $request['url']);
        self::assertStringStartsWith('multipart/form-data; boundary=', $request['headers']['content-type']);
        self::assertStringStartsWith('CloudinaryPHP/', $request['headers']['user-agent']);
        self::assertStringContainsString(file_get_contents(self::TEST_IMAGE_PATH), $request['body']);
        self::assertStringContainsString('name="signature"', $request['body']);
        self::assertStringContainsString('name="tags"', $request['body']);
    }

    /**
     * Should send Admin API calls with Basic auth and parse the JSON response.
     */
    public function testAdminApiThroughPsr18Client()
    {
        $this->useSymfonyClient(
            $this->mockTransport([self::jsonResponse(['public_id' => 'sample', 'bytes' => 3381])])
        );

        $asset = (new AdminApi())->asset('sample');

        self::assertSame(3381, $asset['bytes']);
        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame(self::API_BASE_URL . 'resources/image/upload/sample', $this->requests[0]['url']);
        self::assertSame(
            'Basic ' . base64_encode(self::API_KEY . ':' . self::API_SECRET),
            $this->requests[0]['headers']['authorization']
        );
    }

    /**
     * Should let the user's retrying client resend a failed upload and log the retry.
     */
    public function testUserRetryAndLoggingApplyToUploads()
    {
        $logHandler = new TestHandler();
        $transport  = $this->mockTransport(
            [
                self::jsonResponse(['error' => ['message' => 'Service Unavailable']], 503),
                self::jsonResponse(['public_id' => 'sample']),
            ]
        );

        $this->useSymfonyClient(
            new RetryableHttpClient(
                $transport,
                new GenericRetryStrategy([503], 0),
                1,
                new Logger('app', [$logHandler], [new PsrLogMessageProcessor()])
            )
        );

        $result = (new UploadApi())->upload(self::TEST_IMAGE_PATH);

        self::assertSame('sample', $result['public_id']);
        self::assertCount(2, $this->requests);
        self::assertSame($this->requests[0]['body'], $this->requests[1]['body']);
        self::assertStringContainsString(file_get_contents(self::TEST_IMAGE_PATH), $this->requests[1]['body']);
        $messages = array_map(static fn($record) => $record['message'], $logHandler->getRecords());
        self::assertNotEmpty(preg_grep('/^Try #1 after \d+ms, status code: 503$/', $messages));
    }

    /**
     * Should send every chunk of a large upload through the PSR-18 client.
     */
    public function testChunkedUploadThroughPsr18Client()
    {
        $fileSize  = filesize(self::TEST_IMAGE_PATH);
        $chunkSize = (int)ceil($fileSize / 3);

        $this->useSymfonyClient(
            $this->mockTransport(array_fill(0, 3, self::jsonResponse(['public_id' => 'sample'])))
        );

        $result = (new UploadApi())->upload(self::TEST_IMAGE_PATH, ['chunk_size' => $chunkSize]);

        self::assertSame('sample', $result['public_id']);
        self::assertCount(3, $this->requests);

        $uploadIds = array_unique(array_column(array_column($this->requests, 'headers'), 'x-unique-upload-id'));
        self::assertCount(1, $uploadIds);

        $lastByte = $fileSize - 1;
        self::assertSame("bytes 0-" . ($chunkSize - 1) . "/$fileSize", $this->requests[0]['headers']['content-range']);
        self::assertSame(
            'bytes ' . (2 * $chunkSize) . "-$lastByte/$fileSize",
            $this->requests[2]['headers']['content-range']
        );
    }

    /**
     * Should map API error responses to SDK exceptions.
     */
    public function testApiErrorThroughPsr18Client()
    {
        $this->useSymfonyClient(
            $this->mockTransport([self::jsonResponse(['error' => ['message' => 'Resource not found']], 404)])
        );

        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Resource not found');

        (new AdminApi())->asset('missing');
    }

    /**
     * Should report PSR-18 network errors as Guzzle ConnectException.
     */
    public function testNetworkErrorThroughPsr18Client()
    {
        $this->useSymfonyClient(new MockHttpClient([new MockResponse('', ['error' => 'Could not resolve host'])]));

        $this->expectException(ConnectException::class);
        $this->expectExceptionMessage('Could not resolve host');

        (new AdminApi())->ping();
    }

    /**
     * Should resolve async calls made through the PSR-18 client.
     */
    public function testAsyncCallsThroughPsr18Client()
    {
        $this->useSymfonyClient(
            $this->mockTransport(
                [
                    self::jsonResponse(['public_id' => 'sample']),
                    self::jsonResponse(['status' => 'ok']),
                ]
            )
        );

        [$upload, $ping] = PromiseUtils::all(
            [
                (new UploadApi())->uploadAsync(self::TEST_IMAGE_PATH),
                (new AdminApi())->pingAsync(),
            ]
        )->wait();

        self::assertSame('sample', $upload['public_id']);
        self::assertSame('ok', $ping['status']);
    }

    /**
     * Should send default headers configured on the user's client with every SDK request.
     */
    public function testUserClientDefaultHeadersApply()
    {
        $transport = $this->mockTransport([self::jsonResponse(['status' => 'ok'])]);

        $this->useSymfonyClient($transport->withOptions(['headers' => ['X-Request-Id' => 'req-42']]));

        (new AdminApi())->ping();

        self::assertSame('req-42', $this->requests[0]['headers']['x-request-id']);
    }

    /**
     * Should accept Guzzle itself as the PSR-18 client.
     */
    public function testGuzzleAsPsr18Client()
    {
        $history = [];
        $stack   = HandlerStack::create(new MockHandler([new Response(200, [], '{"status":"ok"}')]));
        $stack->push(Middleware::history($history));

        Cloudinary::setDefaultHttpClient(new GuzzleClient(['handler' => $stack]));

        $result = (new AdminApi())->ping();

        self::assertSame('ok', $result['status']);
        self::assertCount(1, $history);
        self::assertSame(self::API_BASE_URL . 'ping', (string)$history[0]['request']->getUri());
    }

    /**
     * Should send Provisioning API calls through the PSR-18 client with account credentials.
     */
    public function testProvisioningApiThroughPsr18Client()
    {
        $this->useSymfonyClient($this->mockTransport([self::jsonResponse(['users' => []])]));

        $result = (new AccountApi(new ProvisioningConfiguration('account://accountKey:accountSecret@account123')))
            ->users();

        self::assertSame([], $result['users']);
        self::assertSame('GET', $this->requests[0]['method']);
        self::assertStringStartsWith(
            'https://api.cloudinary.com/v1_1/provisioning/accounts/account123/users',
            $this->requests[0]['url']
        );
        self::assertSame(
            'Basic ' . base64_encode('accountKey:accountSecret'),
            $this->requests[0]['headers']['authorization']
        );
    }

    /**
     * Should fetch JSON through the PSR-18 client.
     */
    public function testJsonFetchThroughPsr18Client()
    {
        $this->useSymfonyClient($this->mockTransport([self::jsonResponse(['resources' => [['public_id' => 'a']]])]));

        $json = (new HttpClient())->getJson('https://res.cloudinary.com/test123/image/list/tag.json');

        self::assertSame('a', $json['resources'][0]['public_id']);
        self::assertSame('https://res.cloudinary.com/test123/image/list/tag.json', $this->requests[0]['url']);
    }

    /**
     * Should send the OAuth bearer token instead of Basic auth through the PSR-18 client.
     */
    public function testOAuthTokenThroughPsr18Client()
    {
        $this->useSymfonyClient($this->mockTransport([self::jsonResponse(['status' => 'ok'])]));

        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken('fake_oauth_token');

        (new AdminApi($config))->ping();

        self::assertSame('Bearer fake_oauth_token', $this->requests[0]['headers']['authorization']);
    }

    /**
     * Should restore the default transport when the PSR-18 client is unset.
     */
    public function testUnsetRestoresDefaultTransport()
    {
        Cloudinary::setDefaultHttpClient(new Psr18Client(new MockHttpClient(), new HttpFactory(), new HttpFactory()));
        self::assertArrayHasKey('handler', BaseApiClient::httpHandlerConfig());

        Cloudinary::setDefaultHttpClient(null);
        self::assertSame([], BaseApiClient::httpHandlerConfig());
    }

    /**
     * Should send every API created by a Cloudinary instance through the instance client.
     */
    public function testCloudinaryInstanceClientAppliesToAllApis()
    {
        $client     = self::fakeClient();
        $cloudinary = (new Cloudinary($this->cloudinaryUrl))->setHttpClient($client);

        $cloudinary->uploadApi()->upload(self::TEST_IMAGE_PATH);
        $cloudinary->adminApi()->ping();
        $cloudinary->adminApi()->analyze('uri', 'captioning', 'https://example.com/sample.jpg');
        $cloudinary->searchApi()->expression('tags=a')->execute();
        $cloudinary->searchFoldersApi()->expression('path=a')->execute();

        self::assertSame(
            [
                'v1_1/' . self::CLOUD_NAME . '/image/upload',
                'v1_1/' . self::CLOUD_NAME . '/ping',
                'v2/' . self::CLOUD_NAME . '/analysis/analyze/uri',
                'v1_1/' . self::CLOUD_NAME . '/resources/search',
                'v1_1/' . self::CLOUD_NAME . '/folders/search',
            ],
            $client->paths()
        );
    }

    /**
     * Should prefer the instance client over the process-wide default.
     */
    public function testInstanceClientOverridesDefault()
    {
        $default  = self::fakeClient();
        $instance = self::fakeClient();
        Cloudinary::setDefaultHttpClient($default);

        (new Cloudinary($this->cloudinaryUrl))->setHttpClient($instance)->adminApi()->ping();

        self::assertCount(1, $instance->requests);
        self::assertCount(0, $default->requests);
    }

    /**
     * Should keep clients separate for two Cloudinary instances in one process.
     */
    public function testTwoInstancesUseTheirOwnClients()
    {
        $first  = self::fakeClient();
        $second = self::fakeClient();

        (new Cloudinary($this->cloudinaryUrl))->setHttpClient($first)->adminApi()->ping();
        (new Cloudinary('cloudinary://key2:secret2@other_cloud'))->setHttpClient($second)->adminApi()->ping();

        self::assertSame(['v1_1/' . self::CLOUD_NAME . '/ping'], $first->paths());
        self::assertSame(['v1_1/other_cloud/ping'], $second->paths());
    }

    /**
     * Should use the process-wide default for API objects created directly without a client.
     */
    public function testDefaultAppliesToDirectlyCreatedApis()
    {
        $default = self::fakeClient();
        Cloudinary::setDefaultHttpClient($default);

        (new UploadApi())->upload(self::TEST_IMAGE_PATH);
        (new AdminApi())->ping();

        self::assertCount(2, $default->requests);
    }

    /**
     * Should apply a client set directly on API objects, including the Provisioning API.
     */
    public function testApiObjectSetters()
    {
        $client = self::fakeClient();

        (new UploadApi())->setHttpClient($client)->upload(self::TEST_IMAGE_PATH);
        (new AccountApi(new ProvisioningConfiguration('account://accountKey:accountSecret@account123')))
            ->setHttpClient($client)
            ->users();

        self::assertSame(
            ['v1_1/' . self::CLOUD_NAME . '/image/upload', 'v1_1/provisioning/accounts/account123/users'],
            $client->paths()
        );
    }

    /**
     * Should fall back to the process-wide default when an API object's client is unset.
     */
    public function testUnsetInstanceClientFallsBackToDefault()
    {
        $default  = self::fakeClient();
        $instance = self::fakeClient();
        Cloudinary::setDefaultHttpClient($default);

        $adminApi = (new AdminApi())->setHttpClient($instance);
        $adminApi->ping();
        $adminApi->setHttpClient(null)->ping();

        self::assertCount(1, $instance->requests);
        self::assertCount(1, $default->requests);
    }

    /**
     * Returns a PSR-18 client that records requests and answers every call with a generic success response.
     */
    private static function fakeClient()
    {
        return new class implements ClientInterface {
            public array $requests = [];

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->requests[] = $request;

                return new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    '{"status":"ok","public_id":"sample","users":[],"resources":[]}'
                );
            }

            public function paths(): array
            {
                return array_map(static fn($request) => ltrim($request->getUri()->getPath(), '/'), $this->requests);
            }
        };
    }

    /**
     * Installs a Symfony PSR-18 client wrapping the given Symfony client.
     */
    private function useSymfonyClient($client)
    {
        Cloudinary::setDefaultHttpClient(new Psr18Client($client, new HttpFactory(), new HttpFactory()));
    }

    /**
     * Returns a mock transport that records each request and replies with the queued responses.
     */
    private function mockTransport(array $responses)
    {
        return new MockHttpClient(
            function ($method, $url, $options) use (&$responses) {
                $headers = [];
                foreach ($options['normalized_headers'] as $name => $values) {
                    $headers[$name] = substr($values[0], strlen($name) + 2);
                }

                $this->requests[] = [
                    'method'  => $method,
                    'url'     => $url,
                    'headers' => $headers,
                    'body'    => self::readBody($options['body']),
                ];

                return array_shift($responses);
            }
        );
    }

    /**
     * Reads a Symfony request body, given as a string or a chunk-producing closure.
     */
    private static function readBody($body)
    {
        if (! $body instanceof Closure) {
            return (string)$body;
        }

        $content = '';
        while ('' !== $chunk = $body(16372)) {
            $content .= $chunk;
        }

        return $content;
    }

    /**
     * Builds a JSON mock response.
     */
    private static function jsonResponse(array $data, $status = 200)
    {
        return new MockResponse(
            json_encode($data),
            ['http_code' => $status, 'response_headers' => ['Content-Type: application/json']]
        );
    }
}
