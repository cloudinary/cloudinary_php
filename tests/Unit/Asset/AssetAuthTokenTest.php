<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\AuthToken;
use Cloudinary\Asset\Image;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Transformation\Scale;
use UnexpectedValueException;

/**
 * Class AuthTokenTest
 */
class AssetAuthTokenTest extends AuthTokenTestCase
{
    const EXPECTED_VERSIONED_PATH = self::TEST_ASSET_VERSION_STR.'/'.self::AUTH_TOKEN_TEST_IMAGE;
    /**
     * @var Image $image Test image that is commonly reused by tests
     */
    protected $image;

    public function setUp()
    {
        parent::setUp();

        $this->image                      = new Image(self::AUTH_TOKEN_TEST_IMAGE);
        $this->image->asset->deliveryType = DeliveryType::AUTHENTICATED;
        $this->image->asset->version      = self::TEST_ASSET_VERSION;
    }

    public function testShouldAddTokenIfAuthTokenIsGloballySetAndSignedIsTrue()
    {
        $message = 'Should add token if authToken is globally set and signed = true';

        $expectedToken = '__cld_token__=st=11111111~exp=11111411~'.
                         'hmac=8db0d753ee7bbb9e2eaf8698ca3797436ba4c20e31f44527e43b6a6e995cfdb3';
        self::assertImageUrl(
            self::EXPECTED_VERSIONED_PATH."?$expectedToken",
            $this->image,
            [
                'delivery_type' => DeliveryType::AUTHENTICATED,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testShouldAddTokenForPublicResource()
    {
        $message = "Should add token for 'public' resource";

        $this->image->asset->deliveryType = DeliveryType::PUBLIC_DELIVERY;

        $expectedToken = '__cld_token__=st=11111111~exp=11111411~'.
                         'hmac=c2b77d9f81be6d89b5d0ebc67b671557e88a40bcf03dd4a6997ff4b994ceb80e';
        self::assertImageUrl(
            self::EXPECTED_VERSIONED_PATH."?$expectedToken",
            $this->image,
            [
                'delivery_type' => DeliveryType::PUBLIC_DELIVERY,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testShouldNotAddTokenIfSignedIsFalse()
    {
        $message                         = 'Should not add token if signed is null';
        self::assertImageUrl(
            self::EXPECTED_VERSIONED_PATH,
            $this->image->signUrl(null),
            [
                'delivery_type' => DeliveryType::AUTHENTICATED,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testNullToken()
    {
        $message = 'Should not add token if authToken is globally set but null auth token is explicitly set '.
                   'and signed = true';

        $this->image->authToken->config->key = null;

        $this->image->cloud->apiSecret = 'b';

        self::assertImageUrl(
            's--v2fTPYTu--/'.self::EXPECTED_VERSIONED_PATH,
            $this->image,
            [
                'delivery_type' => DeliveryType::AUTHENTICATED,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testExplicitAuthTokenShouldOverrideGlobalSetting()
    {
        $message = 'Explicit authToken should override global setting';

        $this->image->authToken->config->key       = self::AUTH_TOKEN_ALT_KEY;
        $this->image->authToken->config->startTime = 222222222;
        $this->image->authToken->config->duration  = 100;

        $this->image->resize(Scale::scale(300));

        $this->image->asset->version = null;

        $expectedToken = '__cld_token__=st=222222222~exp=222222322~'.
                         'hmac=55cfe516530461213fe3b3606014533b1eca8ff60aeab79d1bb84c9322eebc1f';

        self::assertImageUrl(
            'c_scale,w_300/'.self::AUTH_TOKEN_TEST_IMAGE."?$expectedToken",
            $this->image,
            [
                'delivery_type' => DeliveryType::AUTHENTICATED,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testShouldComputeExpirationAsStartTimePlusDuration()
    {
        $message = 'Should compute expiration as start time + duration';

        $expectedToken = '__cld_token__=st=11111111~exp=11111411~'.
                         'hmac=8db0d753ee7bbb9e2eaf8698ca3797436ba4c20e31f44527e43b6a6e995cfdb3';
        self::assertImageUrl(
            self::EXPECTED_VERSIONED_PATH."?$expectedToken",
            $this->image,
            [
                'delivery_type' => DeliveryType::AUTHENTICATED,
                'private_cdn'  => true,
                'message'      => $message,
            ]
        );
    }

    public function testShouldThrowWhenAclAndUrlAreMissing()
    {
        $authToken = new AuthToken();
        $authToken->config->startTime = self::START_TIME;
        $authToken->config->duration = self::DURATION;
        $authToken->config->acl = self::AUTH_TOKEN_TEST_CONFIG_ACL;
        $authToken->generate();

        $authToken = new AuthToken();
        $authToken->config->startTime = self::START_TIME;
        $authToken->config->duration = self::DURATION;
        $authToken->generate(self::AUTH_TOKEN_TEST_PATH);

        $authToken = new AuthToken();
        $authToken->config->startTime = self::START_TIME;
        $authToken->config->duration = self::DURATION;

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('AuthToken must contain either acl or url property');

        $authToken->generate();
    }
}
