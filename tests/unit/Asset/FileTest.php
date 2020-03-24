<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Asset;

use Cloudinary\Asset\File;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Cloudinary\AssetTestCase;

/**
 * Class FileTest
 */
final class FileTest extends AssetTestCase
{
    public function testFile()
    {
        $f = new File(self::FILE_NAME);

        self::assertFileUrl(
            self::FILE_NAME,
            (string)$f
        );
    }

    public function testFileSEO()
    {
        $f = new File(self::FILE_NAME);

        $f->asset->suffix = 'my_favorite_sample';

        self::assertAssetUrl(
            'files/sample/my_favorite_sample.bin',
            (string)$f
        );

        $fNoFormat = new File(pathinfo(self::FILE_NAME, PATHINFO_FILENAME));

        $fNoFormat->asset->suffix = 'my_favorite_sample';

        self::assertAssetUrl(
            'files/sample/my_favorite_sample',
            (string)$fNoFormat
        );

        $fNoFormat->deliveryType(DeliveryType::FETCH);

        $this->assertErrorThrowing(
            function () use ($fNoFormat) {
                (string)$fNoFormat;
            }
        );
    }
}
