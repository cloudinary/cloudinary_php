<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ConfigUtils;
use Cloudinary\Test\CloudinaryTestCase;
use Monolog\Handler\TestHandler;
use ReflectionException;
use ReflectionMethod;

/**
 * Class CloudinaryTestCase
 */
abstract class UnitTestCase extends CloudinaryTestCase
{
    const CLOUD_NAME = 'test123';
    const API_KEY    = 'key';
    const API_SECRET = 'secret';

    const SECURE_DIST = 'secure-dist';

    const TEST_LOGGING = ['logging' => ['test' => ['level' => 'debug']]];

    protected $cloudinaryUrl;

    private $cldUrlEnvBackup;

    public function setUp()
    {
        parent::setUp();

        $this->cldUrlEnvBackup = getenv(Configuration::CLOUDINARY_URL_ENV_VAR);

        self::assertNotEmpty($this->cldUrlEnvBackup, 'Please set up CLOUDINARY_URL before running tests!');

        $this->cloudinaryUrl = 'cloudinary://' . $this::API_KEY . ':' . $this::API_SECRET . '@' . $this::CLOUD_NAME;

        putenv(Configuration::CLOUDINARY_URL_ENV_VAR . '=' . $this->cloudinaryUrl);

        $config = ConfigUtils::parseCloudinaryUrl(getenv(Configuration::CLOUDINARY_URL_ENV_VAR));
        $config = array_merge($config, self::TEST_LOGGING);
        Configuration::instance()->init($config);
    }

    public function tearDown()
    {
        parent::tearDown();

        putenv(Configuration::CLOUDINARY_URL_ENV_VAR . '=' . $this->cldUrlEnvBackup);
    }

    /**
     * Asserts that string representations of the objects are equal.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public function assertStrEquals($expected, $actual, $message = '')
    {
        $this->assertEquals((string)$expected, (string)$actual, $message);
    }

    /**
     * Asserts that a given object logged a message of a certain level
     *
     * @param object     $obj     The object that should have logged a message
     * @param string     $message The message that was logged
     * @param string|int $level   Logging level value or name
     *
     * @throws ReflectionException
     */
    protected static function assertObjectLoggedMessage($obj, $message, $level)
    {
        $reflectionMethod = new ReflectionMethod(get_class($obj), 'getLogger');
        $reflectionMethod->setAccessible(true);
        $logger = $reflectionMethod->invoke($obj);
        /** @var TestHandler $testHandler */
        $testHandler = $logger->getTestHandler();

        self::assertInstanceOf(TestHandler::class, $testHandler);
        self::assertTrue(
            $testHandler->hasRecordThatContains($message, $level),
            sprintf('Object %s did not log the message or logged it with a different level', get_class($obj))
        );
    }
}
