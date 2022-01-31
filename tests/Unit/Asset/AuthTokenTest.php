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
use InvalidArgumentException;
use ReflectionMethod;

/**
 * Class AuthTokenTest
 */
class AuthTokenTest extends AuthTokenTestCase
{
    const START_TIME = 1111111111;

    /**
     * @var AuthToken $authToken  The authentication token.
     */
    protected $authToken;

    public function setUp()
    {
        parent::setUp();

        $this->authToken = new AuthToken();
    }


    public function testGenerateWithStartTimeAndDuration()
    {
        $message                      = 'Should generate with start and duration';
        $this->authToken->config->acl = '/image/*';
        $expectedToken                = '__cld_token__=st=1111111111~exp=1111111411~acl=%2fimage%2f*'.
                                        '~hmac=1751370bcc6cfe9e03f30dd1a9722ba0f2cdca283fa3e6df3342a00a7528cc51';
        self::assertEquals(
            $expectedToken,
            $this->authToken->generate(),
            $message
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMustProvideExpirationOrDuration()
    {
        $message                             = 'Should throw if expiration and duration are not provided';
        $this->authToken->config->expiration = null;
        $this->authToken->config->duration   = null;
        $this->authToken->generate();
        $this->fail($message);
    }

    public function testShouldIgnoreUrlIfAclIsProvided()
    {
        $urlToken = $this->authToken->generate(self::IMAGE_NAME);

        $this->authToken->config->acl = '/image/*';
        $aclToken                     = $this->authToken->generate();

        self::assertNotEquals(
            $aclToken,
            $urlToken
        );

        $aclTokenUrlIgnored = $this->authToken->generate(self::IMAGE_NAME);

        self::assertEquals(
            $aclToken,
            $aclTokenUrlIgnored
        );
    }

    /**
     * Should escape a URL using lowercase hex symbols
     */
    public function testEscapeToLower()
    {
        $method = new ReflectionMethod(AuthToken::class, 'escapeToLower');
        $method->setAccessible(true);

        self::assertEquals(
            'Encode%20these%20%3a%7e%40%23%25%5e%26%7b%7d%5b%5d%5c%22%27%3b%2f%22,%20but%20not%20those%20$!()_.*',
            $method->invoke(null, 'Encode these :~@#%^&{}[]\\"\';/", but not those $!()_.*')
        );
    }
}
