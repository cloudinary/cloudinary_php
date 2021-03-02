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
 * Class TagTest
 */
final class TagTest extends IntegrationTestCase
{
    private static $TAG_TO_ADD_TO_IMAGE;
    private static $TAG_TO_REPLACE_ALL_TAGS;
    private static $TAG_TO_REMOVE;

    const REMOVE_ALL_TAGS  = 'remove_all_tags';
    const REMOVE_ALL_TAGS2 = 'remove_all_tags2';
    const REPLACE_ALL_TAGS = 'replace_all_tags';

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$TAG_TO_ADD_TO_IMAGE        = 'upload_tag_add_tag_test_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_REPLACE_ALL_TAGS    = 'upload_tag_replace_all_tags_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_REMOVE              = 'upload_tag_remove_tag_' . self::$UNIQUE_TEST_TAG;

        self::createTestAssets(
            [
                self::REMOVE_ALL_TAGS  => ['cleanup' => true],
                self::REMOVE_ALL_TAGS2 => ['cleanup' => true],
                self::REPLACE_ALL_TAGS => [
                    'options' => ['tags' => [self::$TAG_TO_REPLACE_ALL_TAGS]],
                    'cleanup' => true
                ],
            ]
        );

        self::uploadTestAssetImage(
            [
                'tags'      => [self::$TAG_TO_REMOVE],
                'public_id' => self::$UNIQUE_TEST_ID,
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Add a tag to images with matching Public IDs
     */
    public function testAddTagToImagesByPublicID()
    {
        $result = self::$uploadApi->addTag(
            self::$TAG_TO_ADD_TO_IMAGE,
            [
                self::$UNIQUE_TEST_ID,
            ]
        );

        self::assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $asset = self::$adminApi->asset(self::$UNIQUE_TEST_ID);

        self::assertContains(self::$TAG_TO_ADD_TO_IMAGE, $asset['tags']);
    }

    /**
     * Remove a tag from images by Public IDs
     */
    public function testRemoveTagFromImagesByPublicID()
    {
        $result = self::$uploadApi->removeTag(self::$TAG_TO_REMOVE, [self::$UNIQUE_TEST_ID]);

        self::assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $asset = self::$adminApi->asset(self::$UNIQUE_TEST_ID);

        self::assertNotContains(self::$TAG_TO_REMOVE, $asset['tags']);
    }

    /**
     * Remove all tags from images by Public IDs
     */
    public function testRemoveAllTagFromImagesByPublicID()
    {
        $result = self::$uploadApi->removeAllTags([self::getTestAssetPublicId(self::REMOVE_ALL_TAGS)]);

        self::assertEquals([self::getTestAssetPublicId(self::REMOVE_ALL_TAGS)], $result['public_ids']);

        $asset = self::$adminApi->asset(self::getTestAssetPublicId(self::REMOVE_ALL_TAGS));

        self::assertArrayNotHasKey('tags', $asset);

        $multipleIds = [
            self::getTestAssetPublicId(self::REMOVE_ALL_TAGS),
            self::getTestAssetPublicId(self::REMOVE_ALL_TAGS2)
        ];

        $result = self::$uploadApi->removeAllTags($multipleIds);

        self::assertEquals($multipleIds, $result['public_ids']);

        $asset = self::$adminApi->asset(self::getTestAssetPublicId(self::REMOVE_ALL_TAGS2));

        self::assertArrayNotHasKey('tags', $asset);
    }

    /**
     * Replace all existing tags with one tag for the images by Public IDs
     */
    public function testReplaceAllTagsForImagesByPublicID()
    {
        $result = self::$uploadApi->replaceTag(
            self::$UNIQUE_TEST_TAG,
            [self::getTestAssetPublicId(self::REPLACE_ALL_TAGS)]
        );

        self::assertEquals([self::getTestAssetPublicId(self::REPLACE_ALL_TAGS)], $result['public_ids']);

        $asset = self::$adminApi->asset(self::getTestAssetPublicId(self::REPLACE_ALL_TAGS));

        self::assertEquals([self::$UNIQUE_TEST_TAG], $asset['tags']);
    }
}
