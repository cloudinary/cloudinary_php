<?php

/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Utils;

use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\FileUtils;
use Cloudinary\Tag\ImageTag;
use PHPUnit\Framework\TestCase;

/**
 * Class FileUtilsTest
 */
final class FileUtilsTest extends TestCase
{
    public function testIsLocalFilePath()
    {
        $remoteFiles = [
            'ftp://ftp.cloudinary.com/images/old_logo.png',
            'http://cloudinary.com/images/old_logo.png',
            'https://cloudinary.com/images/old_logo.png',
            's3://s3-us-west-2.amazonaws.com/cloudinary/images/old_logo.png',
            'gs://cloudinary/images/old_logo.png',
            'data:image/gif;charset=utf-8;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            'data:image/gif;param1=value1;param2=value2;' .
            'base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            'data:image/svg+xml;charset=utf-8;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPg',
            'data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;charset=utf-8;' .
            'base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPg',
            ImageTag::BLANK,
        ];

        foreach ($remoteFiles as $file) {
            self::assertFalse(FileUtils::isLocalFilePath($file));
        }

        $localFiles = [
            '/etc/passwd',
            '/usr/local',
            'C:\\\\Program Files\\Program\\image.jpg',
        ];

        foreach ($localFiles as $file) {
            self::assertTrue(FileUtils::isLocalFilePath($file));
        }
    }

    public function testSplitFilenameExtension()
    {
        $files = [
            ['file.ext', [null, 'file', 'ext']],
            ['file', [null, 'file', null]],
            ['file.not_ext', [null, 'file.not_ext', null]],
            ['path/to/file.ext', ['path/to', 'file', 'ext']],
        ];

        foreach ($files as $file) {
            self::assertEquals($file[1], FileUtils::splitPathFilenameExtension($file[0]));
        }
    }

    public function testSafeFileOpenWithExistingFile()
    {
        $existingFile = __DIR__ . '/../../assets/sample.png';
        $resource = FileUtils::safeFileOpen($existingFile, 'rb');

        self::assertIsResource($resource);
        self::assertEquals('stream', get_resource_type($resource));

        fclose($resource);
    }

    public function testSafeFileOpenWithNonExistingFile()
    {
        $nonExistingFile = __DIR__ . '/../../assets/non_existing_file.txt';

        $this->expectException(GeneralError::class);
        FileUtils::safeFileOpen($nonExistingFile, 'rb');
    }

    public function testSafeFileOpenWithNonExistingFolder()
    {
        $nonExistingFile = 'foo/bar';

        $this->expectException(GeneralError::class);
        FileUtils::safeFileOpen($nonExistingFile, 'rb');
    }

    public function testSafeFileOpenWithEmptyPath()
    {
        $this->expectException(GeneralError::class);
        $this->expectExceptionMessage('Path cannot be empty');

        FileUtils::safeFileOpen('', 'rb');
    }
}
