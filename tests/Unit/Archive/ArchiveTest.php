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
use Cloudinary\Asset\AssetType;
use Cloudinary\Test\Unit\UnitTestCase;

class ArchiveTest extends UnitTestCase
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
        self::assertContains('image', $url);

        // should return valid url.
        $url = self::$uploadApi->downloadFolder('folder/');
        self::assertContains('generate_archive', $url);

        // should flatten folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['flatten_folders' => true]);
        self::assertContains('flatten_folders', $url);

        // should expire_at folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['expires_at' => time() + 60]);
        self::assertContains('expires_at', $url);

        // should use original file_name of folder.
        $url = self::$uploadApi->downloadFolder('folder/', ['use_original_filename' => true]);
        self::assertContains('use_original_filename', $url);
    }
}
