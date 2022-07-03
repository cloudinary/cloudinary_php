<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Utils;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Utils;
use Cloudinary\Utils\SignatureVerifier;
use InvalidArgumentException;

/**
 * Class SignatureVerifierTest
 */
class SignatureVerifierTest extends AssetTestCase
{
    const TEST_VERSION = 1;
    const API_SECRET   = 'X7qLTrsES31MzxxkxPPA-pAGGfU';

    const TEST_IMG = 'tests/logo.png';

    public static $mockedNow;

    protected static $notificationBody = '{"notification_type":"eager","eager":[{"transformation":"sp_full_hd/mp4",' .
                                         '"bytes":1055,"url":"http://res.cloudinary.com/demo/video/upload/sp_full_hd/' .
                                         'v1533125278/dog.mp4","secure_url":"https://res.cloudinary.com/demo/video/' .
                                         'upload/sp_full_hd/v1533125278/dog.mp4"}],"public_id":"dog",' .
                                         '"batch_id":"9b11fa058c61fa577f4ec516bf6ee756ac2aefef09' .
                                         '5af99aef1302142cc1694a"}';

    protected static $timestamp;
    protected static $notificationSignature = 'dfe82de1d9083fe0b7ea68070649f9a15b8874da';
    protected static $validFor              = 60;

    protected static $publicId             = self::TEST_IMG;
    protected static $apiResponseSignature = '08d3107a5b2ad82e7d82c0b972218fbf20b5b1e0';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$mockedNow = 1549533574;
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        self::$mockedNow = null;
    }

    public function setUp()
    {
        parent::setUp();

        self::$timestamp = Utils::unixTimeNow() - self::$validFor;

        Configuration::instance()->cloud->apiSecret = self::API_SECRET;
    }

    public function testSuccessfulNotificationSignatureVerification()
    {
        $signatureTestValues = [
            ['timestamp' => self::$timestamp, 'validFor' => self::$validFor],
            ['timestamp' => (string)self::$timestamp, 'validFor' => (string)self::$validFor],
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerifier::verifyNotificationSignature(
                self::$notificationBody,
                $value['timestamp'],
                self::$notificationSignature,
                $value['validFor']
            );

            self::assertTrue($result);
        }
    }

    public function testFailedNotificationSignatureVerification()
    {
        $signatureTestValues = [
            [
                'body'      => '{' . self::$notificationBody . '}',
                'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => self::$timestamp - 1,
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature . 'a',
            ],
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerifier::verifyNotificationSignature(
                $value['body'],
                $value['timestamp'],
                $value['signature']
            );

            self::assertFalse($result);
        }
    }

    public function testDefaultValidFor()
    {
        $result = SignatureVerifier::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature
        );

        self::assertTrue($result);
    }

    public function testExpiredTimestamp()
    {
        $reducedValidFor = self::$validFor - 1;

        $result = SignatureVerifier::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature,
            $reducedValidFor
        );

        self::assertFalse($result);
    }

    public function testNotificationMissingApiSecret()
    {
        Configuration::instance()->cloud->apiSecret = null;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API Secret is invalid');

        SignatureVerifier::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature
        );
    }

    public function testNotificationInvalidParameter()
    {
        $invalidValues = [
            [
                'body'      => null,
                'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => null,
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => self::$timestamp,
                'signature' => null,
            ],
            [
                'body'      => [self::$notificationBody],
                'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => [self::$timestamp],
                'signature' => self::$notificationSignature,
            ],
            [
                'body'      => self::$notificationBody,
                'timestamp' => self::$timestamp,
                'signature' => [self::$notificationSignature],
            ],
        ];

        foreach ($invalidValues as $value) {
            $success = false;
            try {
                SignatureVerifier::verifyNotificationSignature(
                    $value['body'],
                    $value['timestamp'],
                    $value['signature']
                );
            } catch (InvalidArgumentException $e) {
                $success = true;
            }
            self::assertTrue($success);
        }
    }

    public function testSuccessfulApiResponseSignatureVerification()
    {
        $signatureTestValues = [self::TEST_VERSION, (string)self::TEST_VERSION];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerifier::verifyApiResponseSignature(
                self::$publicId,
                $value,
                self::$apiResponseSignature
            );

            self::assertTrue($result);
        }
    }

    public function testFailedApiResponseSignatureVerification()
    {
        $signatureTestValues = [
            [
                'publicId'  => self::$publicId . 'a',
                'version'   => self::TEST_VERSION,
                'signature' => self::$apiResponseSignature,
            ],
            [
                'publicId'  => self::$publicId,
                'version'   => self::TEST_VERSION + 1,
                'signature' => self::$apiResponseSignature,
            ],
            [
                'publicId'  => self::$publicId,
                'version'   => self::TEST_VERSION,
                'signature' => self::$apiResponseSignature . 'a',
            ],
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerifier::verifyApiResponseSignature(
                $value['publicId'],
                $value['version'],
                $value['signature']
            );

            self::assertFalse($result);
        }
    }

    public function testApiResponseMissingApiSecret()
    {
        Configuration::instance()->cloud->apiSecret = null;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API Secret is invalid');

        SignatureVerifier::verifyApiResponseSignature(
            self::$publicId,
            self::TEST_VERSION,
            self::$apiResponseSignature
        );
    }
}

namespace Cloudinary;

use Cloudinary\Test\Unit\Utils\SignatureVerifierTest;

/**
 * Mock for time() function
 *
 * FIXME: use mock instead
 *
 * @return int  With test timestamp associated with the signature in the notifications test
 */
function time()
{
    return SignatureVerifierTest::$mockedNow ?: \time();
}
