<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Api\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\FileUtils;
use Cloudinary\Test\Api\IntegrationTestCase;
use Cloudinary\Test\Cloudinary\AssetTestCase;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\StreamInterface;

use function GuzzleHttp\Psr7\stream_for;

/**
 * Class UploadApiTest
 */
final class UploadApiTest extends IntegrationTestCase
{
    const TEST_IMAGE_WIDTH  = 241;
    const TEST_IMAGE_HEIGHT = 51;

    const LARGE_CHUNK_SIZE = 5243000;

    const LARGE_TEST_IMAGE_SIZE   = 5880138;
    const LARGE_TEST_IMAGE_WIDTH  = 1400;
    const LARGE_TEST_IMAGE_HEIGHT = 1400;

    const SMALL_TEST_IMAGE_WIDTH  = 1;
    const SMALL_TEST_IMAGE_HEIGHT = 1;

    private static $LARGE_TEST_IMAGE_ID;
    private static $REMOTE_TEST_IMAGE_ID;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$LARGE_TEST_IMAGE_ID  = 'upload_large_' . self::$UNIQUE_TEST_ID;
        self::$REMOTE_TEST_IMAGE_ID = 'upload_remote_' . self::$UNIQUE_TEST_ID;

        $uploadPreset = self::$adminApi->createUploadPreset(
            [
                'name'            => self::$UNIQUE_UPLOAD_PRESET,
                'tags'            => self::$ASSET_TAGS,
                'unsigned'        => true,
                'allowed_formats' => 'gif',
            ]
        );

        self::assertUploadPresetCreation($uploadPreset);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupUploadPreset(self::$UNIQUE_UPLOAD_PRESET);

        parent::tearDownAfterClass();
    }

    /**
     * @throws ApiError
     */
    public function testUpload()
    {
        $result = self::$uploadApi->upload(self::TEST_IMAGE_PATH, ['tags' => self::$ASSET_TAGS]);

        self::assertValidResource(
            $result,
            [
                'width'  => self::TEST_IMAGE_WIDTH,
                'height' => self::TEST_IMAGE_HEIGHT,
            ]
        );
    }

    /**
     * @throws ApiError
     */
    public function testUploadAsync()
    {
        $p = self::$uploadApi->uploadAsync(self::TEST_IMAGE_PATH, ['tags' => self::$ASSET_TAGS]);

        $result = $p->wait();

        self::assertValidResource(
            $result,
            [
                'width'  => self::TEST_IMAGE_WIDTH,
                'height' => self::TEST_IMAGE_HEIGHT,
            ]
        );
    }

    /**
     * @throws ApiError
     */
    public function testUploadNonExistingFile()
    {
        $this->expectException(GeneralError::class);
        $this->expectExceptionMessage('No such file or directory');
        self::$uploadApi->upload(self::TEST_IMAGE_PATH . '_non_existing');
    }

    /**
     * @throws ApiError
     */
    public function testUploadFileHandle()
    {
        $fd = FileUtils::safeFileOpen(self::TEST_IMAGE_PATH, 'rb');

        $result = self::$uploadApi->upload($fd, ['tags' => self::$ASSET_TAGS]);

        self::assertValidResource(
            $result,
            [
                'width'  => self::TEST_IMAGE_WIDTH,
                'height' => self::TEST_IMAGE_HEIGHT,
            ]
        );
    }

    /**
     * @throws ApiError
     */
    public function testUploadBase64()
    {
        $result = self::$uploadApi->upload(
            self::TEST_BASE64_IMAGE,
            ['filename' => self::$UNIQUE_TEST_ID, 'tags' => self::$ASSET_TAGS]
        );

        self::assertValidResource(
            $result,
            [
                'width'  => self::SMALL_TEST_IMAGE_WIDTH,
                'height' => self::SMALL_TEST_IMAGE_HEIGHT,
            ]
        );
        self::assertArrayNotHasKey('original_filename', $result);
    }

    /**
     * @throws ApiError
     */
    public function testUploadUrl()
    {
        $result = self::$uploadApi->upload(AssetTestCase::FETCH_IMAGE_URL, ['tags' => self::$ASSET_TAGS]);

        self::assertValidResource($result, ['original_filename' => AssetTestCase::ASSET_ID]);

        $result = self::$uploadApi->upload(new Uri(AssetTestCase::FETCH_IMAGE_URL), ['tags' => self::$ASSET_TAGS]);

        self::assertValidResource($result, ['original_filename' => AssetTestCase::ASSET_ID]);
    }

    /**
     * @throws ApiError
     */
    public function testUploadGSUrl()
    {
        $this->markTestSkipped('Check GS authorization issue');

        $gsImage = 'gs://gcp-public-data-landsat/LC08/PRE/044/034/LC80440342016259LGN00/LC80440342016259LGN00_BQA.TIF';
        $result  = self::$uploadApi->upload($gsImage, ['tags' => self::$ASSET_TAGS]);

        $this->assertEquals($gsImage, $result['original_filename']);
    }

    /**
     * @throws ApiError
     */
    public function testUploadLarge()
    {
        $result = self::$uploadApi->upload(
            self::populateLargeImage(),
            [
                'filename'        => self::$LARGE_TEST_IMAGE_ID,
                'tags'            => self::$ASSET_TAGS,
                'chunk_size'      => self::LARGE_CHUNK_SIZE,
                'use_filename'    => true,
                'unique_filename' => false,
            ]
        );

        self::assertValidResource(
            $result,
            [
                'original_filename' => self::$LARGE_TEST_IMAGE_ID,
                'width'             => self::LARGE_TEST_IMAGE_WIDTH,
                'height'            => self::LARGE_TEST_IMAGE_HEIGHT,
            ]
        );
    }

    /**
     * @throws ApiError
     */
    public function testUploadNonSeekableStream()
    {
        $stream = fopen(AssetTestCase::FETCH_IMAGE_URL, 'rb'); // open http socket and pass it to uploader

        $result = self::$uploadApi->upload(
            $stream,
            [
                'filename'        => self::$REMOTE_TEST_IMAGE_ID,
                'tags'            => self::$ASSET_TAGS,
                'use_filename'    => true,
                'unique_filename' => false,
            ]
        );

        self::assertValidResource($result, ['original_filename' => self::$REMOTE_TEST_IMAGE_ID]);
    }

    /**
     * @return StreamInterface
     */
    protected static function populateLargeImage()
    {
        $head = "BMJ\xB9Y\x00\x00\x00\x00\x00\x8A\x00\x00\x00|\x00\x00\x00x\x05\x00\x00x\x05\x00\x00\x01\x00\x18\x00" .
                "\x00\x00\x00\x00\xC0\xB8Y\x00a\x0F\x00\x00a\x0F\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xFF" .
                "\x00\x00\xFF\x00\x00\xFF\x00\x00\x00\x00\x00\x00\xFFBGRs\x00\x00\x00\x00\x00\x00\x00\x00T\xB8\x1E" .
                "\xFC\x00\x00\x00\x00\x00\x00\x00\x00fff\xFC\x00\x00\x00\x00\x00\x00\x00\x00\xC4\xF5(\xFF\x00\x00\x00" .
                "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x04\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";

        $largeStream = stream_for($head . str_repeat("\xFF", self::LARGE_TEST_IMAGE_SIZE - strlen($head)));
        $largeStream->rewind();

        return $largeStream;
    }

    /**
     * Unsigned upload of an image
     *
     * @throws ApiError
     */
    public function testUploadUnsignedImage()
    {
        $result = self::$uploadApi->unsignedUpload(
            self::TEST_BASE64_IMAGE,
            self::$UNIQUE_UPLOAD_PRESET,
            ['tags' => [self::$UNIQUE_TEST_TAG]]
        );

        self::assertValidResource($result, ['format' => 'gif']);
    }

    /**
     * Add a metadata field to the image with the Public ID
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#metadata_method
     */
    public function testAddTheMetadataFieldImagesByPublicID()
    {
        $this->markTestIncomplete('This functionality is not implemented yet');
    }

    /**
     * Get the accessibility analysis of an uploaded image
     *
     * @throws ApiError
     */
    public function testAccessibilityAnalysis()
    {
        $result = self::uploadTestResourceImage(['accessibility_analysis' => true], self::TEST_IMAGE_PATH);

        $this->assertArrayHasKey('accessibility_analysis', $result);
    }
}
