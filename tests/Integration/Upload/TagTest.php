<?php

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
    private static $REMOVE_ALL_TAGS_PUBLIC_ID;
    private static $REPLACE_ALL_TAGS_PUBLIC_ID;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$TAG_TO_ADD_TO_IMAGE = 'upload_tag_add_tag_test_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_REPLACE_ALL_TAGS = 'upload_tag_replace_all_tags_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_REMOVE = 'upload_tag_remove_tag_' . self::$UNIQUE_TEST_TAG;
        self::$REMOVE_ALL_TAGS_PUBLIC_ID = 'upload_tag_public_id_to_remove_all_tags_' . self::$UNIQUE_TEST_ID;
        self::$REPLACE_ALL_TAGS_PUBLIC_ID = 'upload_tag_public_id_to_replace_all_tags_' . self::$UNIQUE_TEST_ID;
        self::addResourceToCleanupList(self::$REMOVE_ALL_TAGS_PUBLIC_ID);
        self::addResourceToCleanupList(self::$REPLACE_ALL_TAGS_PUBLIC_ID);

        self::uploadTestResourceImage(
            [
                'tags' => [self::$TAG_TO_REMOVE],
                'public_id' => self::$UNIQUE_TEST_ID
            ]
        );
        self::uploadTestResourceImage(['public_id' => self::$REMOVE_ALL_TAGS_PUBLIC_ID]);
        self::uploadTestResourceImage(
            [
                'tags' => [self::$TAG_TO_REPLACE_ALL_TAGS],
                'public_id' => self::$REPLACE_ALL_TAGS_PUBLIC_ID
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupResources();

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
                self::$UNIQUE_TEST_ID
            ]
        );

        $this->assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$UNIQUE_TEST_ID);

        $this->assertContains(self::$TAG_TO_ADD_TO_IMAGE, $resource['tags']);
    }

    /**
     * Remove a tag from images by Public IDs
     */
    public function testRemoveTagFromImagesByPublicID()
    {
        $result = self::$uploadApi->removeTag(self::$TAG_TO_REMOVE, [self::$UNIQUE_TEST_ID]);

        $this->assertEquals([self::$UNIQUE_TEST_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$UNIQUE_TEST_ID);

        $this->assertNotContains(self::$TAG_TO_REMOVE, $resource['tags']);
    }

    /**
     * Remove all tags from images by Public IDs
     */
    public function testRemoveAllTagFromImagesByPublicID()
    {
        $result = self::$uploadApi->removeAllTags([self::$REMOVE_ALL_TAGS_PUBLIC_ID]);

        $this->assertEquals([self::$REMOVE_ALL_TAGS_PUBLIC_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$REMOVE_ALL_TAGS_PUBLIC_ID);

        $this->assertArrayNotHasKey('tags', $resource);
    }

    /**
     * Replace all existing tags with one tag for the images by Public IDs
     */
    public function testReplaceAllTagsForImagesByPublicID()
    {
        $result = self::$uploadApi->replaceTag(self::$UNIQUE_TEST_TAG, [self::$REPLACE_ALL_TAGS_PUBLIC_ID]);

        $this->assertEquals([self::$REPLACE_ALL_TAGS_PUBLIC_ID], $result['public_ids']);

        $resource = self::$adminApi->resource(self::$REPLACE_ALL_TAGS_PUBLIC_ID);

        $this->assertEquals([self::$UNIQUE_TEST_TAG], $resource['tags']);
    }
}
