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
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Integration\IntegrationTestCase;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\RetryableHttpClient;

/**
 * Sends SDK requests to a live cloud through PSR-18 clients.
 */
final class Psr18ClientTest extends IntegrationTestCase
{
    public function tearDown()
    {
        self::useHttpClientFromEnv();

        parent::tearDown();
    }

    /**
     * Should upload, fetch and delete an asset through a Symfony client with the user's logging and retries.
     */
    public function testAssetLifecycleThroughSymfonyPsr18Client()
    {
        $logHandler = new TestHandler();
        $logger     = new Logger('app', [$logHandler], [new PsrLogMessageProcessor()]);

        $transport = HttpClient::create();
        $transport->setLogger($logger);

        Cloudinary::setDefaultHttpClient(
            new Psr18Client(new RetryableHttpClient($transport, null, 2, $logger), new HttpFactory(), new HttpFactory())
        );

        $publicId  = 'psr18_' . self::$UNIQUE_TEST_ID;
        $uploadApi = new UploadApi();

        $upload = $uploadApi->upload(
            self::TEST_IMAGE_PATH,
            ['public_id' => $publicId, 'tags' => self::$ASSET_TAGS]
        );
        self::assertSame($publicId, $upload['public_id']);

        $asset = (new AdminApi())->asset($publicId);
        self::assertSame($upload['asset_id'], $asset['asset_id']);

        $destroy = $uploadApi->destroy($publicId);
        self::assertSame('ok', $destroy['result']);

        $cloudName = Configuration::instance()->cloud->cloudName;
        $messages  = array_map(static fn($record) => $record['message'], $logHandler->getRecords());
        self::assertContains(
            "Request: \"POST https://api.cloudinary.com/v1_1/$cloudName/image/upload\"",
            $messages
        );
        self::assertContains(
            "Request: \"GET https://api.cloudinary.com/v1_1/$cloudName/resources/image/upload/$publicId\"",
            $messages
        );
    }

    /**
     * Should call the Admin API through Guzzle used as a plain PSR-18 client.
     */
    public function testPingThroughGuzzlePsr18Client()
    {
        Cloudinary::setDefaultHttpClient(new GuzzleClient());

        $result = (new AdminApi())->ping();

        self::assertSame('ok', $result['status']);
    }
}
