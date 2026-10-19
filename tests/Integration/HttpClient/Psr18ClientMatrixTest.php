<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\HttpClient;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\AuthorizationRequired;
use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Asset\AssetType;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\Utils as PromiseUtils;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * Runs real-world SDK scenarios against a live cloud through each supported PSR-18 client.
 */
final class Psr18ClientMatrixTest extends IntegrationTestCase
{
    const LARGE_IMAGE_SIZE   = 5880138;
    const LARGE_IMAGE_WIDTH  = 1400;
    const LARGE_CHUNK_SIZE   = 5243000;
    const SAMPLE_IMAGE_WIDTH = 241;

    /**
     * @var UploadApi Upload API bound to the client under test.
     */
    private $upload;

    /**
     * @var AdminApi Admin API bound to the client under test.
     */
    private $admin;

    public static function tearDownAfterClass()
    {
        Cloudinary::setDefaultHttpClient(null);
        self::cleanupTestAssets([AssetType::IMAGE, AssetType::RAW]);

        parent::tearDownAfterClass();
    }

    public function tearDown()
    {
        self::useHttpClientFromEnv();

        parent::tearDown();
    }

    /**
     * PSR-18 clients under test.
     */
    public function clients()
    {
        return [
            'guzzle'  => ['guzzle'],
            'symfony' => ['symfony'],
        ];
    }

    /**
     * Should upload a local file as multipart with tags, context and a custom filename.
     *
     * @dataProvider clients
     */
    public function testMultipartUploadWithFields($client)
    {
        $this->useClient($client);
        $publicId = $this->publicId('multipart', $client);

        $result = $this->upload->upload(
            self::TEST_IMAGE_PATH,
            [
                'public_id'    => $publicId,
                'tags'         => array_merge(self::$ASSET_TAGS, ["psr18_$client"]),
                'context'      => ['caption' => "Caption via $client", 'alt' => 'alt text'],
                'filename'     => "custom file $client.png",
                'use_filename' => true,
                'headers'      => ['X-Robots-Tag: noindex'],
            ]
        );

        self::assertSame($publicId, $result['public_id']);
        self::assertSame("custom file $client", $result['original_filename']);
        self::assertSame(self::SAMPLE_IMAGE_WIDTH, $result['width']);

        $asset = $this->admin->asset($publicId, ['context' => true, 'tags' => true]);
        self::assertContains("psr18_$client", $asset['tags']);
        self::assertSame("Caption via $client", $asset['context']['custom']['caption']);
        self::assertSame('alt text', $asset['context']['custom']['alt']);
    }

    /**
     * Should upload in-memory content given as a PHP stream, a PSR-7 stream, a data URI and a remote URL.
     *
     * @dataProvider clients
     */
    public function testBlobUploads($client)
    {
        $this->useClient($client);
        $bytes = file_get_contents(self::TEST_IMAGE_PATH);

        $resource = fopen('php://memory', 'r+b');
        fwrite($resource, $bytes);
        rewind($resource);

        $sources = [
            'php-stream' => [$resource, ['filename' => 'blob.png']],
            'psr7-stream' => [Psr7\Utils::streamFor($bytes), ['filename' => 'blob.png']],
            'data-uri'   => [self::TEST_BASE64_IMAGE, []],
            'remote-url' => [AssetTestCase::FETCH_IMAGE_URL, []],
        ];

        foreach ($sources as $label => [$file, $options]) {
            $result = $this->upload->upload($file, array_merge(['tags' => self::$ASSET_TAGS], $options));

            self::assertArrayHasKey('public_id', $result, $label);
            self::assertGreaterThan(0, $result['width'], $label);
            self::assertSame('image', $result['resource_type'], $label);
        }
    }

    /**
     * Should upload a large file in chunks.
     *
     * @dataProvider clients
     */
    public function testChunkedLargeUpload($client)
    {
        $recorder     = self::recordingClient(self::createClient($client));
        $this->upload = (new UploadApi())->setHttpClient($recorder);

        $result = $this->upload->upload(
            self::largeImage(),
            [
                'public_id'  => $this->publicId('large', $client),
                'filename'   => 'large.bmp',
                'tags'       => self::$ASSET_TAGS,
                'chunk_size' => self::LARGE_CHUNK_SIZE,
            ]
        );

        self::assertSame(self::LARGE_IMAGE_WIDTH, $result['width']);
        self::assertSame(self::LARGE_IMAGE_WIDTH, $result['height']);

        $lastByte = self::LARGE_IMAGE_SIZE - 1;
        self::assertSame(
            [
                'bytes 0-' . (self::LARGE_CHUNK_SIZE - 1) . '/' . self::LARGE_IMAGE_SIZE,
                'bytes ' . self::LARGE_CHUNK_SIZE . "-$lastByte/" . self::LARGE_IMAGE_SIZE,
            ],
            array_map(static fn($request) => $request->getHeaderLine('Content-Range'), $recorder->requests)
        );
    }

    /**
     * Should create a structured metadata field, set and update its value on an asset, and delete the field.
     *
     * @dataProvider clients
     */
    public function testStructuredMetadata($client)
    {
        $this->useClient($client);
        $externalId = 'psr18_field_' . $client . '_' . self::$UNIQUE_TEST_ID;
        $publicId   = $this->publicId('metadata', $client);

        $field = new StringMetadataField("PSR-18 $client");
        $field->setExternalId($externalId);
        $created = $this->admin->addMetadataField($field);
        self::assertSame($externalId, $created['external_id']);

        try {
            $uploaded = $this->upload->upload(
                self::TEST_IMAGE_PATH,
                ['public_id' => $publicId, 'tags' => self::$ASSET_TAGS, 'metadata' => [$externalId => 'first']]
            );
            self::assertSame('first', $uploaded['metadata'][$externalId]);

            $updated = $this->upload->updateMetadata([$externalId => 'second | with = chars'], [$publicId]);
            self::assertSame([$publicId], $updated['public_ids']);

            $asset = $this->admin->asset($publicId);
            self::assertSame('second | with = chars', $asset['metadata'][$externalId]);
        } finally {
            $deleted = $this->admin->deleteMetadataField($externalId);
            self::assertSame('ok', $deleted['message']);
        }
    }

    /**
     * Should send array parameters in query strings and page through results.
     *
     * @dataProvider clients
     */
    public function testArrayParametersAndPaging($client)
    {
        $this->useClient($client);
        $tag = 'psr18_paging_' . $client . '_' . self::$UNIQUE_TEST_ID;

        $publicIds = [];
        foreach (range(1, 3) as $i) {
            $publicIds[] = $this->upload->upload(
                self::TEST_IMAGE_PATH,
                ['public_id' => $this->publicId("paging_$i", $client), 'tags' => array_merge(self::$ASSET_TAGS, [$tag])]
            )['public_id'];
        }

        $first = $this->admin->assetsByTag($tag, ['max_results' => 2]);
        self::assertCount(2, $first['resources']);
        self::assertNotEmpty($first['next_cursor']);

        $second = $this->admin->assetsByTag($tag, ['max_results' => 2, 'next_cursor' => $first['next_cursor']]);
        self::assertCount(1, $second['resources']);

        $listed = array_column(array_merge($first['resources'], $second['resources']), 'public_id');
        sort($listed);
        sort($publicIds);
        self::assertSame($publicIds, $listed);

        $deleted = $this->admin->deleteAssets($publicIds);
        self::assertSame(array_fill_keys($publicIds, 'deleted'), $deleted['deleted']);
    }

    /**
     * Should report a missing asset as NotFound.
     *
     * @dataProvider clients
     */
    public function testNotFound($client)
    {
        $this->useClient($client);

        $this->expectException(NotFound::class);

        $this->admin->asset($this->publicId('missing', $client));
    }

    /**
     * Should report a wrong API secret as AuthorizationRequired on Admin and Upload APIs.
     *
     * @dataProvider clients
     */
    public function testWrongSecret($client)
    {
        $this->useClient($client);
        $config = new Configuration(Configuration::instance());
        $config->cloud->apiSecret = 'wrong_secret';

        $failures = [];
        foreach (['admin' => fn() => (new AdminApi($config))->ping(),
                  'upload' => fn() => (new UploadApi($config))->upload(self::TEST_IMAGE_PATH)] as $api => $call) {
            try {
                $call();
            } catch (AuthorizationRequired $e) {
                $failures[] = $api;
            }
        }

        self::assertSame(['admin', 'upload'], $failures);
    }

    /**
     * Should report invalid content and an empty file as BadRequest.
     *
     * @dataProvider clients
     */
    public function testBadRequests($client)
    {
        $this->useClient($client);

        $messages = [];
        foreach (['invalid' => 'not an image', 'empty' => ''] as $label => $content) {
            try {
                $this->upload->upload(Psr7\Utils::streamFor($content), ['filename' => 'bad.png']);
            } catch (BadRequest $e) {
                $messages[$label] = $e->getMessage();
            }
        }

        self::assertSame(
            ['invalid' => 'Invalid image file', 'empty' => 'Empty file'],
            $messages
        );
    }

    /**
     * Should report an unresolvable API host as ConnectException.
     *
     * @dataProvider clients
     */
    public function testUnresolvableHost($client)
    {
        $this->useClient($client);
        $config = new Configuration(Configuration::instance());
        $config->api->uploadPrefix = 'https://cloudinary-psr18-test.invalid';

        $this->expectException(ConnectException::class);
        $this->expectExceptionMessage('cloudinary-psr18-test.invalid');

        (new AdminApi($config))->ping();
    }

    /**
     * Should round-trip Unicode public IDs and context values with reserved characters.
     *
     * @dataProvider clients
     */
    public function testUnicodeAndReservedCharacters($client)
    {
        $this->useClient($client);
        $publicId = "psr18_ünïcødé_日本語_{$client}_" . self::$UNIQUE_TEST_ID;
        $caption  = 'Ünï "quoted" | piped = equals & ampersand';

        $result = $this->upload->upload(
            self::TEST_IMAGE_PATH,
            ['public_id' => $publicId, 'tags' => self::$ASSET_TAGS, 'context' => ['caption' => $caption]]
        );

        self::assertSame($publicId, $result['public_id']);
        self::assertSame($caption, $result['context']['custom']['caption']);
        self::assertSame($publicId, $this->admin->asset($publicId)['public_id']);
    }

    /**
     * Should expose response headers and rate limits from the client's response.
     *
     * @dataProvider clients
     */
    public function testResponseHeadersAndRateLimits($client)
    {
        $this->useClient($client);

        $result = $this->admin->ping();

        self::assertSame('ok', $result['status']);
        self::assertNotEmpty($result->headers);
        self::assertGreaterThan(0, $result->rateLimitAllowed);
        self::assertGreaterThanOrEqual(0, $result->rateLimitRemaining);
    }

    /**
     * Should resolve async upload and Admin calls.
     *
     * @dataProvider clients
     */
    public function testAsyncCalls($client)
    {
        $this->useClient($client);

        [$first, $second, $ping] = PromiseUtils::all(
            [
                $this->upload->uploadAsync(self::TEST_IMAGE_PATH, ['tags' => self::$ASSET_TAGS]),
                $this->upload->uploadAsync(self::TEST_IMAGE_GIF_PATH, ['tags' => self::$ASSET_TAGS]),
                $this->admin->pingAsync(),
            ]
        )->wait();

        self::assertSame('png', $first['format']);
        self::assertSame('gif', $second['format']);
        self::assertSame('ok', $ping['status']);
    }

    /**
     * Creates API objects bound to the named PSR-18 client.
     */
    private function useClient($name)
    {
        $httpClient = self::createClient($name);

        $this->upload = (new UploadApi())->setHttpClient($httpClient);
        $this->admin  = (new AdminApi())->setHttpClient($httpClient);
    }

    /**
     * Creates a PSR-18 client by name.
     */
    private static function createClient($name): ClientInterface
    {
        $factory = new HttpFactory();

        switch ($name) {
            case 'guzzle':
                return new GuzzleClient();
            default:
                return new Psr18Client(HttpClient::create(), $factory, $factory);
        }
    }

    /**
     * Wraps a PSR-18 client to record the requests it sends.
     */
    private static function recordingClient(ClientInterface $client)
    {
        return new class ($client) implements ClientInterface {
            public array $requests = [];

            private ClientInterface $client;

            public function __construct(ClientInterface $client)
            {
                $this->client = $client;
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->requests[] = $request;

                return $this->client->sendRequest($request);
            }
        };
    }

    /**
     * Builds a public ID unique to this run and client.
     */
    private function publicId($scenario, $client)
    {
        return "psr18_{$scenario}_{$client}_" . self::$UNIQUE_TEST_ID;
    }

    /**
     * Generates a large BMP image stream.
     */
    private static function largeImage()
    {
        $head = "BMJ\xB9Y\x00\x00\x00\x00\x00\x8A\x00\x00\x00|\x00\x00\x00x\x05\x00\x00x\x05\x00\x00\x01\x00\x18\x00" .
                "\x00\x00\x00\x00\xC0\xB8Y\x00a\x0F\x00\x00a\x0F\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xFF" .
                "\x00\x00\xFF\x00\x00\xFF\x00\x00\x00\x00\x00\x00\xFFBGRs\x00\x00\x00\x00\x00\x00\x00\x00T\xB8\x1E" .
                "\xFC\x00\x00\x00\x00\x00\x00\x00\x00fff\xFC\x00\x00\x00\x00\x00\x00\x00\x00\xC4\xF5(\xFF\x00\x00\x00" .
                "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x04\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";

        return Psr7\Utils::streamFor($head . str_repeat("\xFF", self::LARGE_IMAGE_SIZE - strlen($head)));
    }
}
