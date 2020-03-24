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
use Cloudinary\Test\CloudinaryTestCase;

/**
 * Class CloudinaryTestCase
 */
abstract class UnitTestCase extends CloudinaryTestCase
{
    const CLOUD_NAME = 'test123';
    const API_KEY    = 'key';
    const API_SECRET = 'secret';

    const SECURE_DIST = 'secure-dist';

    protected $cloudinaryUrl;

    private $cldUrlEnvBackup;

    public function setUp()
    {
        parent::setUp();

        $this->cldUrlEnvBackup = getenv(Configuration::CLOUDINARY_URL_ENV_VAR);

        self::assertNotEmpty($this->cldUrlEnvBackup, 'Please set up CLOUDINARY_URL before running tests!');

        $this->cloudinaryUrl = 'cloudinary://' . $this::API_KEY . ':' . $this::API_SECRET . '@' . $this::CLOUD_NAME;

        putenv(Configuration::CLOUDINARY_URL_ENV_VAR . '=' . $this->cloudinaryUrl);
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
}
