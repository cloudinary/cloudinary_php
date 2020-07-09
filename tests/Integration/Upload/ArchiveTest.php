<?php

namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Asset\AssetType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use ZipArchive;

/**
 * Class ArchiveTest
 */
final class ArchiveTest extends IntegrationTestCase
{
    private static $ARCHIVE_TEST_TAG;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$ARCHIVE_TEST_TAG = 'upload_archive_' . self::$UNIQUE_TEST_TAG;

        $tags = [
            self::$ARCHIVE_TEST_TAG,
        ];

        self::uploadTestResourceImage(['tags' => $tags, 'public_id' => self::$UNIQUE_TEST_ID]);
        self::uploadTestResourceImage(['tags' => $tags]);
        self::uploadTestResourceFile(['tags' => $tags]);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupTestResources(AssetType::RAW);

        parent::tearDownAfterClass();
    }

    /**
     * Create an archive file that contains all resources that have certain tags and are of a certain asset type
     */
    public function testCreateArchiveByTag()
    {
        $archive = self::$uploadApi->createArchive(
            [
                'tags' => self::$ARCHIVE_TEST_TAG,
                AssetType::KEY => AssetType::RAW,
                'target_tags' => self::$UNIQUE_TEST_TAG,
                'target_format' => 'tgz'
            ]
        );

        self::assertValidArchive(
            $archive,
            'tgz',
            [
                'resource_count' => 1,
                'file_count' => 1,
            ]
        );
        $this->assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
    }

    /**
     * Create an archive file that contains all images (the default type asset type) that have certain tags
     */
    public function testCreateArchiveOfDefaultTypeByTag()
    {
        $archive = self::$uploadApi->createArchive(
            [
                'tags' => self::$ARCHIVE_TEST_TAG,
                'target_tags' => self::$UNIQUE_TEST_TAG
            ]
        );

        self::assertValidArchive(
            $archive,
            'zip',
            [
                'resource_count' => 2,
                'file_count' => 2,
            ]
        );
        $this->assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
    }

    /**
     * Create a zip file that contains all images that have certain tags
     */
    public function testCreateZipByTag()
    {
        $zip = self::$uploadApi->createZip(
            [
                'tags' => self::$ARCHIVE_TEST_TAG,
                AssetType::KEY => AssetType::IMAGE,
                'target_tags' => self::$UNIQUE_TEST_TAG
            ]
        );

        self::assertValidArchive(
            $zip,
            'zip',
            [
                'resource_count' => 2,
                'file_count' => 2,
            ]
        );
        $this->assertContains(self::$UNIQUE_TEST_TAG, $zip['tags']);
    }

    /**
     * Create an archive file that contains resources of different types
     */
    public function testCreateArchiveMultipleResourceTypes()
    {
        // FIXME: add video and raw files
        $testIds = [
            'image/upload/' . self::$UNIQUE_TEST_ID
        ];

        $archive = self::$uploadApi->createZip(
            [
                'resource_type' => AssetType::AUTO,
                'fully_qualified_public_ids' => $testIds,
                'target_tags' => self::$UNIQUE_TEST_TAG
            ]
        );

        self::assertValidArchive(
            $archive,
            'zip',
            [
                'resource_count' => 1,
                'file_count' => 1,
            ]
        );

        $this->assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
    }

    /**
     * Generate a URL for downloading a zip file
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#generate_archive_method
     */
    public function testGenerateUrlForDownloadZip()
    {
        $result = self::$uploadApi->downloadZipUrl(
            [
                'tags' => self::$ARCHIVE_TEST_TAG,
            ]
        );

        try {
            $file = tempnam('.', 'zip');
            file_put_contents($file, file_get_contents($result));
            $zip = new ZipArchive();
            $zip->open($file);
            $numFiles = $zip->numFiles;
            unset($zip);
        } finally {
            unlink($file);
        }

        $this->assertEquals(2, $numFiles);
    }

    /**
     * Generate a URL for downloading an archive file
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#generate_archive_method
     *
     * TODO: check different resource_type and target_format
     */
    public function testGenerateUrlForDownloadArchive()
    {
        $result = self::$uploadApi->downloadArchiveUrl(
            [
                'tags' => self::$ARCHIVE_TEST_TAG,
                'target_format' => 'zip',
                'resource_type' => AssetType::IMAGE,
            ]
        );

        try {
            $file = tempnam('.', 'zip');
            file_put_contents($file, file_get_contents($result));
            $zip = new ZipArchive();
            $zip->open($file);
            $numFiles = $zip->numFiles;
            unset($zip);
        } finally {
            unlink($file);
        }

        $this->assertEquals(2, $numFiles);
    }
}
