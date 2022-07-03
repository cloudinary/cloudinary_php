<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Archive;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Upload\UploadEndPoint;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class ArchiveTest
 */
class ArchiveTest extends AssetTestCase
{
    /**
     * @var UploadApi $uploadApi
     */
    private static $uploadApi;

    public static function setUpBeforeClass()
    {
        self::$uploadApi = new UploadApi();
    }

    /**
     * Checks URLs for downloading a folder as an archive file.
     */
    public function testDownloadFolder()
    {
        // should return url with resource_type image.
        $url = self::$uploadApi->downloadFolder('samples/', ['resource_type' => AssetType::IMAGE]);
        self::assertStringContainsString('image', $url);

        // should return valid url.
        $url = self::$uploadApi->downloadFolder('folder/');
        self::assertStringContainsString('generate_archive', $url);

        // should flatten folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['flatten_folders' => true]);
        self::assertStringContainsString('flatten_folders', $url);

        // should expire_at folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['expires_at' => time() + 60]);
        self::assertStringContainsString('expires_at', $url);

        // should use original file_name of folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['use_original_filename' => true]);
        self::assertStringContainsString('use_original_filename', $url);
    }

    public function testPrivateDownloadUrl()
    {
        $attachmentName = 'my_attachment_name';
        $expirationTime = time() + 60;

        $url = self::$uploadApi->privateDownloadUrl(
            self::ASSET_ID,
            self::IMG_EXT,
            [
                "type"       => DeliveryType::UPLOAD,
                "attachment" => $attachmentName,
                "expires_at" => $expirationTime,
            ]
        );

        $expectedParts = [
            AssetType::IMAGE . '/' . UploadEndPoint::DOWNLOAD,
            'public_id=' . self::ASSET_ID,
            'format=' . self::IMG_EXT,
            'type=' . DeliveryType::UPLOAD,
            'attachment=' . $attachmentName,
            'expires_at=' . $expirationTime,
            'timestamp',
            'signature',
            'api_key',
        ];

        foreach ($expectedParts as $expectedPart) {
            self::assertStringContainsString($expectedPart, $url);
        }
    }

    public function testPrivateDownloadUrlAssetType()
    {
        $videoUrl = self::$uploadApi->privateDownloadUrl(
            self::ASSET_ID,
            self::VID_EXT,
            [
                'resource_type' => AssetType::VIDEO,
            ]
        );

        self::assertStringContainsString(AssetType::VIDEO . '/' . UploadEndPoint::DOWNLOAD, $videoUrl);
    }
}
