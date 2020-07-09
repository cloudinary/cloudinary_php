<?php

namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class ContextTest
 */
final class ContextTest extends IntegrationTestCase
{
    const CONTEXT_DATA = ['k1' => 'v2', 'k2' => 'v2'];

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::uploadTestResourceImage(['public_id' => self::$UNIQUE_TEST_ID]);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();

        parent::tearDownAfterClass();
    }

    /**
     * Add a context to images by Public IDs
     */
    public function testAddContextToImagesByPublicID()
    {
        $result = self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);

        $this->assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$UNIQUE_TEST_ID);

        $this->assertEquals(self::CONTEXT_DATA, $resource['context']['custom']);
    }

    /**
     * Remove all context from images by Public IDs array
     */
    public function testRemoveAllContextFromImagesByPublicIDsArray()
    {
        self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);

        $result = self::$uploadApi->removeAllContext([self::$UNIQUE_TEST_ID]);

        $this->assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$UNIQUE_TEST_ID);

        $this->assertArrayNotHasKey('context', $resource);
    }

    /**
     * Remove all context from image by Public ID string
     */
    public function testRemoveAllContextFromImagesByPublicIDString()
    {
        self::$uploadApi->addContext(self::CONTEXT_DATA, [self::$UNIQUE_TEST_ID]);

        $result = self::$uploadApi->removeAllContext(self::$UNIQUE_TEST_ID);

        $this->assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$UNIQUE_TEST_ID);

        $this->assertArrayNotHasKey('context', $resource);
    }
}
