<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class AspectRatio
 */
class AspectRatio extends BaseQualifier
{
    const MAX_VALUES = 2;

    const AR_1X1         = '1:1';
    const AR_5X4         = '5:4';
    const AR_4X3         = '4:3';
    const AR_3X2         = '3:2';
    const AR_16X9        = '16:9';
    const AR_3X1         = '3:1';
    const IGNORE_INITIAL = "ignore_aspect_ratio";

    /**
     * Aspect ratio 1:1.
     *
     * @return AspectRatio
     */
    public static function ar1x1()
    {
        return new self(self::AR_1X1);
    }

    /**
     * Aspect ratio 5:4.
     *
     * @return AspectRatio
     */
    public static function ar5x4()
    {
        return new self(self::AR_5X4);
    }

    /**
     * Aspect ratio 4:3.
     *
     * @return AspectRatio
     */
    public static function ar4x3()
    {
        return new self(self::AR_4X3);
    }

    /**
     * Aspect ratio 3:2.
     *
     * @return AspectRatio
     */
    public static function ar3x2()
    {
        return new self(self::AR_3X2);
    }

    /**
     * Aspect ratio 16:9.
     *
     * @return AspectRatio
     */
    public static function ar16x9()
    {
        return new self(self::AR_16X9);
    }

    /**
     * Aspect ratio 3:1.
     *
     * @return AspectRatio
     */
    public static function ar3x1()
    {
        return new self(self::AR_3X1);
    }

    /**
     * Set to ignore initial aspect ratio.
     *
     * @return AspectRatio
     */
    public static function ignoreInitialAspectRatio()
    {
        return new self(self::IGNORE_INITIAL);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        if ((string)$this->value === self::IGNORE_INITIAL) {
            return "";
        }

        return parent::__toString();
    }

}
