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

/**
 * Defines the custom colors to use when resizing using content-aware padding.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#content_aware_padding
 * target="_blank">Content-aware padding with custom color palette</a>
 *
 * @api
 */
class AutoBackgroundMode extends QualifierMultiValue
{
    const VALUE_DELIMITER = '_';

    const TYPE     = 'type';
    const CONTRAST = 'contrast';

    protected $argumentOrder = [self::TYPE, self::CONTRAST];

    /**
     * AutoBackgroundMode constructor.
     *
     * @param string $type The type of the background mode.
     */
    public function __construct($type)
    {
        parent::__construct();

        $this->type($type);
    }

    /**
     * Determines which color is automatically chosen for the background.
     *
     * @param string $type Use the constants defined in this class.
     *
     * @return AutoBackgroundMode
     */
    public function type($type)
    {
        $this->setSimpleValue(self::TYPE, $type);

        return $this;
    }

    /**
     * Inverse the color.
     *
     * @return AutoBackgroundMode
     */
    public function contrast()
    {
        return $this->setSimpleValue(self::CONTRAST, self::CONTRAST);
    }
}
