<?php
namespace Cloudinary\Test;

use Cloudinary\SignatureVerificator;
use PHPUnit\Framework\TestCase;

/**
 * Class SignatureVerificatorTest
 * @package Cloudinary
 */
class SignatureVerificatorTest extends TestCase
{
    const TEST_VERSION = 1;
    const API_SECRET = 'X7qLTrsES31MzxxkxPPA-pAGGfU';

    //Cannot concat within a class property in PHP < 5.6, therefore it remains in 1 line
    protected static $notificationBody = '{"notification_type":"eager","eager":[{"transformation":"sp_full_hd/mp4","bytes":1055,"url":"http://res.cloudinary.com/demo/video/upload/sp_full_hd/v1533125278/dog.mp4","secure_url":"https://res.cloudinary.com/demo/video/upload/sp_full_hd/v1533125278/dog.mp4"}],"public_id":"dog","batch_id":"9b11fa058c61fa577f4ec516bf6ee756ac2aefef095af99aef1302142cc1694a"}';
    protected static $timestamp;
    protected static $notificationSignature = 'dfe82de1d9083fe0b7ea68070649f9a15b8874da';
    protected static $validFor = 60;

    protected static $publicId = \Cloudinary\TEST_IMG;
    protected static $apiResponseSignature = '08d3107a5b2ad82e7d82c0b972218fbf20b5b1e0';

    public function setUp()
    {
        \Cloudinary::reset_config();

        self::$timestamp = \Cloudinary\time() - self::$validFor;

        \Cloudinary::config(['api_secret' => self::API_SECRET]);
    }

    public function tearDown()
    {
        \Cloudinary::reset_config();
    }

    public function testSuccessfulNotificationSignatureVerification()
    {
        $signatureTestValues = [
            ['timestamp' => self::$timestamp, 'validFor' => self::$validFor],
            ['timestamp' => (string)self::$timestamp, 'validFor' => (string)self::$validFor]
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerificator::verifyNotificationSignature(
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
            ['body' => '{' . self::$notificationBody . '}', 'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => self::$timestamp - 1,
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature . 'a']
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerificator::verifyNotificationSignature(
                $value['body'],
                $value['timestamp'],
                $value['signature']
            );

            self::assertFalse($result);
        }
    }

    public function testDefaultValidFor()
    {
        $result = SignatureVerificator::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature
        );

        self::assertTrue($result);
    }

    public function testExpiredTimestamp()
    {
        $reducedValidFor = self::$validFor - 1;

        $result = SignatureVerificator::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature,
            $reducedValidFor
        );

        self::assertFalse($result);
    }

    /**
     * @expectedException           \InvalidArgumentException
     * @expectedExceptionMessage    API Secret is invalid
     */
    public function testNotificationMissingApiSecret()
    {
        \Cloudinary::config(['api_secret' => null]);

        SignatureVerificator::verifyNotificationSignature(
            self::$notificationBody,
            self::$timestamp,
            self::$notificationSignature
        );
    }

    public function testNotificationInvalidParameter()
    {
        $invalidValues = [
            ['body' => null, 'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => null,
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => self::$timestamp,
                'signature' => null],
            ['body' => [self::$notificationBody], 'timestamp' => self::$timestamp,
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => [self::$timestamp],
                'signature' => self::$notificationSignature],
            ['body' => self::$notificationBody, 'timestamp' => self::$timestamp,
                'signature' => [self::$notificationSignature]]
        ];

        foreach ($invalidValues as $value) {
            $success = false;
            try {
                SignatureVerificator::verifyNotificationSignature(
                    $value['body'],
                    $value['timestamp'],
                    $value['signature']
                );
            } catch (\InvalidArgumentException $e) {
                $success = true;
            }
            self::assertTrue($success);
        }
    }

    public function testSuccessfulApiResponseSignatureVerification()
    {
        $signatureTestValues = [self::TEST_VERSION, (string)self::TEST_VERSION];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerificator::verifyApiResponseSignature(
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
            ['$publicId' => self::$publicId . 'a', 'version' => self::TEST_VERSION,
                'signature' => self::$apiResponseSignature],
            ['$publicId' => self::$publicId, 'version' => self::TEST_VERSION + 1,
                'signature' => self::$apiResponseSignature],
            ['$publicId' => self::$publicId, 'version' => self::TEST_VERSION,
                'signature' => self::$apiResponseSignature . 'a']
        ];

        foreach ($signatureTestValues as $value) {
            $result = SignatureVerificator::verifyApiResponseSignature(
                $value['$publicId'],
                $value['version'],
                $value['signature']
            );

            self::assertFalse($result);
        }
    }

    /**
     * @expectedException           \InvalidArgumentException
     * @expectedExceptionMessage    API Secret is invalid
     */
    public function testApiResponseMissingApiSecret()
    {
        \Cloudinary::config(['api_secret' => null]);

        SignatureVerificator::verifyApiResponseSignature(
            self::$publicId,
            self::TEST_VERSION,
            self::$apiResponseSignature
        );
    }
}

namespace Cloudinary;

/**
 * Mock for time() function
 *
 * @return int  With test timestamp associated with the signature in the notifications test
 */
function time()
{
    return 1549533574;
}