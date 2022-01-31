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
use Cloudinary\Asset\AssetType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use ZipArchive;

/**
 * Class ArchiveTest
 */
final class ArchiveTest extends IntegrationTestCase
{
    const ARCHIVE_IMAGE_1 = 'archive_image_1';
    const ARCHIVE_IMAGE_2 = 'archive_image_2';
    const ARCHIVE_RAW     = 'archive_raw';

    private static $ARCHIVE_TEST_TAG;
    private static $ARCHIVE_TEST_TAG2;
    private static $ARCHIVE_TEST_TAGS;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$ARCHIVE_TEST_TAG  = 'upload_archive_1_' . self::$UNIQUE_TEST_TAG;
        self::$ARCHIVE_TEST_TAG2 = 'upload_archive_2_' . self::$UNIQUE_TEST_TAG;
        self::$ARCHIVE_TEST_TAGS = [
            self::$ARCHIVE_TEST_TAG,
            self::$ARCHIVE_TEST_TAG2,
        ];

        self::createTestAssets(
            [
                self::ARCHIVE_IMAGE_1 => [
                    'options' => ['tags' => [self::$ARCHIVE_TEST_TAG]],
                ],
                self::ARCHIVE_IMAGE_2 => [
                    'options' => ['tags' => [self::$ARCHIVE_TEST_TAG2]],
                ],
                self::ARCHIVE_RAW     => [
                    'options' => [
                        AssetType::KEY => AssetType::RAW,
                        'file'         => self::TEST_DOCX_PATH,
                        'tags'         => [self::$ARCHIVE_TEST_TAG],
                    ],
                ],
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets([AssetType::IMAGE, AssetType::RAW]);

        parent::tearDownAfterClass();
    }

    /**
     * Create an archive file that contains all resources that have certain tags and are of a certain asset type
     */
    public function testCreateArchiveByTag()
    {
        $archive = self::$uploadApi->createArchive(
            [
                'tags'          => self::$ARCHIVE_TEST_TAG,
                AssetType::KEY  => AssetType::RAW,
                'target_tags'   => self::$UNIQUE_TEST_TAG,
                'target_format' => 'tgz',
            ]
        );

        self::assertValidArchive(
            $archive,
            'tgz',
            [
                'resource_count' => 1,
                'file_count'     => 1,
            ]
        );
        self::assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
    }

    /**
     * Create an archive file that contains all images (the default type asset type) that have certain tags
     */
    public function testCreateArchiveOfDefaultTypeByTag()
    {
        $archive = self::$uploadApi->createArchive(
            [
                'tags'        => self::$ARCHIVE_TEST_TAGS,
                'target_tags' => self::$UNIQUE_TEST_TAG,
            ]
        );

        self::assertValidArchive(
            $archive,
            'zip',
            [
                'resource_count' => 2,
                'file_count'     => 2,
            ]
        );
        self::assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
    }

    /**
     * Create a zip file that contains all images that have certain tags
     */
    public function testCreateZipByTag()
    {
        $zip = self::$uploadApi->createZip(
            [
                'tags'         => self::$ARCHIVE_TEST_TAGS,
                AssetType::KEY => AssetType::IMAGE,
                'target_tags'  => self::$UNIQUE_TEST_TAG,
            ]
        );

        self::assertValidArchive(
            $zip,
            'zip',
            [
                'resource_count' => 2,
                'file_count'     => 2,
            ]
        );
        self::assertContains(self::$UNIQUE_TEST_TAG, $zip['tags']);
    }

    /**
     * Create an archive file that contains resources of different types
     */
    public function testCreateArchiveMultipleAssetTypes()
    {
        $testIds = [
            'image/upload/' . self::getTestAssetPublicId(self::ARCHIVE_IMAGE_1),
            'raw/upload/' . self::getTestAssetPublicId(self::ARCHIVE_RAW),
        ];

        $archive = self::$uploadApi->createZip(
            [
                'resource_type'              => AssetType::AUTO,
                'fully_qualified_public_ids' => $testIds,
                'target_tags'                => self::$UNIQUE_TEST_TAG,
            ]
        );

        self::assertValidArchive(
            $archive,
            'zip',
            [
                'resource_count' => 2,
                'file_count'     => 2,
            ]
        );

        self::assertContains(self::$UNIQUE_TEST_TAG, $archive['tags']);
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
                'tags' => self::$ARCHIVE_TEST_TAGS,
            ]
        );

        self::assertZipUrlContainsFiles($result, 2);
    }

    /**
     * Generate a URL for downloading a zip file using provided public_ids
     */
    public function testDownloadZipUrlMultiplePublicIds()
    {
        $result = self::$uploadApi->downloadZipUrl(
            [
                'public_ids' => [
                    self::getTestAssetPublicId(self::ARCHIVE_IMAGE_1),
                    self::getTestAssetPublicId(self::ARCHIVE_IMAGE_2),
                ],
            ]
        );

        self::assertZipUrlContainsFiles($result, 2);
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
                'tags'          => self::$ARCHIVE_TEST_TAGS,
                'target_format' => 'zip',
                'resource_type' => AssetType::IMAGE,
            ]
        );

        self::assertZipUrlContainsFiles($result, 2);
    }

    /**
     * Helper methods that downloads a zip file and asserts number of files in it.
     *
     * @param string $url      The URL with the zip archive.
     * @param int    $numFiles The number of files to expect.
     */
    protected static function assertZipUrlContainsFiles($url, $numFiles)
    {
        try {
            $file = tempnam('.', 'zip');
            file_put_contents($file, file_get_contents($url));
            $zip = new ZipArchive();
            $zip->open($file);
            $actualFiles = $zip->numFiles;
            unset($zip);
        } finally {
            unlink($file);
        }

        self::assertEquals($numFiles, $actualFiles);
    }
}
