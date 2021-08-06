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

    const ADDING_CONTEXT_TO_IMAGE = 'adding_context_to_image';
    const REMOVING_ALL_CONTEXT_1  = 'removing_all_context_1';
    const REMOVING_ALL_CONTEXT_2  = 'removing_all_context_2';
    const REMOVING_ALL_CONTEXT_3  = 'removing_all_context_3';

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::createTestAssets(
            [
                self::ADDING_CONTEXT_TO_IMAGE,
                self::REMOVING_ALL_CONTEXT_1 => ['options' => ['context' => self::CONTEXT_DATA]],
                self::REMOVING_ALL_CONTEXT_2 => ['options' => ['context' => self::CONTEXT_DATA]],
                self::REMOVING_ALL_CONTEXT_3 => ['options' => ['context' => self::CONTEXT_DATA]],
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Add a context to images by Public IDs.
     */
    public function testAddContextToImagesByPublicID()
    {
        $result = self::$uploadApi->addContext(
            self::CONTEXT_DATA,
            [
                self::getTestAssetPublicId(self::ADDING_CONTEXT_TO_IMAGE)
            ]
        );

        self::assertEquals(
            [
                self::getTestAssetPublicId(self::ADDING_CONTEXT_TO_IMAGE)
            ],
            $result['public_ids']
        );

        $asset = self::$adminApi->asset(self::getTestAssetPublicId(self::ADDING_CONTEXT_TO_IMAGE));

        self::assertEquals(self::CONTEXT_DATA, $asset['context']['custom']);
    }

    /**
     * Remove all context from images by Public IDs array.
     */
    public function testRemoveAllContextFromImagesByPublicIDsArray()
    {
        $asset1 = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_1)
        );
        $asset2 = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_2)
        );

        self::assertArrayHasKey('context', $asset1);
        self::assertArrayHasKey('context', $asset2);

        $result = self::$uploadApi->removeAllContext(
            [
                self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_1),
                self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_2),
            ]
        );

        self::assertEquals(
            [
                self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_1),
                self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_2),
            ],
            $result['public_ids']
        );

        $asset1 = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_1)
        );
        $asset2 = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_2)
        );

        self::assertArrayNotHasKey('context', $asset1);
        self::assertArrayNotHasKey('context', $asset2);
    }

    /**
     * Remove all context from image by Public ID string.
     */
    public function testRemoveAllContextFromImagesByPublicIDString()
    {
        $asset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_3)
        );

        self::assertArrayHasKey('context', $asset);

        $result = self::$uploadApi->removeAllContext(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_3)
        );

        self::assertEquals(
            [
                self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_3)
            ],
            $result['public_ids']
        );

        $asset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::REMOVING_ALL_CONTEXT_3)
        );

        self::assertArrayNotHasKey('context', $asset);
    }
}
