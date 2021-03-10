<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class ContextTest
 */
final class ContextTest extends IntegrationTestCase
{
    const CONTEXT_DATA = ['k1' => 'v2', 'k2' => 'vשаß = & | ? !@#$%^&*()_+{}:">?/.,\']['];

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::uploadTestAssetImage(['public_id' => self::$UNIQUE_TEST_ID]);
        self::uploadTestAssetImage(['public_id' => self::$UNIQUE_TEST_ID2]);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Add a context to images by Public IDs
     */
    public function testAddContextToImagesByPublicID()
    {
        $result = self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);

        self::assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $asset = self::$adminApi->asset(self::$UNIQUE_TEST_ID);

        self::assertEquals(self::CONTEXT_DATA, $asset['context']['custom']);
    }

    /**
     * Remove all context from images by Public IDs array
     */
    public function testRemoveAllContextFromImagesByPublicIDsArray()
    {
        self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);
        self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID2]);

        $result = self::$uploadApi->removeAllContext([self::$UNIQUE_TEST_ID, self::$UNIQUE_TEST_ID2]);

        self::assertEquals([self::$UNIQUE_TEST_ID, self::$UNIQUE_TEST_ID2], $result['public_ids']);

        $resource = self::$adminApi->asset(self::$UNIQUE_TEST_ID);
        $resource2 = self::$adminApi->asset(self::$UNIQUE_TEST_ID2);

        self::assertArrayNotHasKey('context', $resource);
        self::assertArrayNotHasKey('context', $resource2);
    }

    /**
     * Remove all context from image by Public ID string
     */
    public function testRemoveAllContextFromImagesByPublicIDString()
    {
        self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);

        $result = self::$uploadApi->removeAllContext(self::$UNIQUE_TEST_ID);

        self::assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $asset = self::$adminApi->asset(self::$UNIQUE_TEST_ID);

        self::assertArrayNotHasKey('context', $asset);
    }
}
