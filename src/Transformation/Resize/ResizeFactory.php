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

use UnexpectedValueException;

/**
 * Class ResizeFactory
 */
class ResizeFactory
{
    /**
     * @var array $resizeTypes Supported types of the resize.
     */
    private static $resizeTypes = ['Scale', 'Crop', 'Pad', 'Fill', 'FillPad', 'Imagga']; // TODO: load this dynamically

    /**
     * @var array $resizeModes Supported resize(crop) modes.
     */
    private static $resizeModes;

    /**
     * Populates resize (crop) modes.
     */
    private static function populateModes()
    {
        foreach (self::$resizeTypes as $type) {
            $typeClass = __NAMESPACE__ . "\\$type";

            $currTypeModes = get_class_methods($typeClass);

            foreach ($currTypeModes as $currModeName => $currModeVal) {
                self::$resizeModes[$currModeVal] = $type;
            }
        }
    }

    /**
     * Creates a Resize instance from mode name.
     *
     * @param CropMode|string $mode  The resize(crop) mode.
     * @param mixed           $width Optional. Width.
     * @param mixed           $height Optional. Height.
     *
     * @return BaseResizeAction
     */
    public static function createResize($mode, $width = null, $height = null)
    {
        if (empty(self::$resizeModes)) {
            self::populateModes();
        }

        if (! array_key_exists($mode, self::$resizeModes)) {
            throw new UnexpectedValueException("Unsupported resize mode {$mode}");
        }

        $resizeType = __NAMESPACE__ . "\\" . self::$resizeModes[$mode];

        return new $resizeType($mode, $width, $height);
    }
}
