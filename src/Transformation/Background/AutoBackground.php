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

use Cloudinary\ClassUtils;

/**
 * Automatically sets the background color when resizing with padding.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#content_aware_padding" target="_blank">
 * Content-aware padding</a>
 *
 * @api
 */
class AutoBackground extends Background
{
    const AUTO    = 'auto';
    const MODE    = 'mode';
    const PALETTE = 'palette';

    use AutoBackgroundTrait;

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, self::MODE, self::PALETTE];

    /**
     * Selects the predominant color while taking only the image border pixels into account. (Server default)
     */
    const BORDER = 'border';

    /**
     * Selects the predominant color while taking all pixels in the image into account.
     */
    const PREDOMINANT = 'predominant';

    /**
     * @var string $name The name.
     */
    protected static $name = 'background';

    /**
     * @var string $type The type of the background color. Use the constants defined in this class.
     */
    protected $type;

    /**
     * AutoBackground constructor.
     *
     * @param string $mode The auto background mode.
     */
    public function __construct($mode)
    {
        parent::__construct(self::AUTO);

        $this->mode($mode);
    }

    /**
     * Determines which color is automatically chosen for the background.
     *
     * @param string $mode Use the constants defined in this class.
     *
     * @return AutoBackground
     */
    protected function mode($mode)
    {
        $this->value->setSimpleValue(self::MODE, ClassUtils::forceInstance($mode, AutoBackgroundMode::class));

        return $this;
    }

    /**
     * Determines which color is automatically chosen for the background.
     *
     * @param string $type Use the constants defined in this class.
     *
     * @return AutoBackground
     */
    public function type($type)
    {
        $this->value->getSimpleValue(self::MODE)->type($type);

        return $this;
    }

    /**
     * Inverse the color.
     *
     * @return AutoBackground
     */
    public function contrast()
    {
        $this->value->getSimpleValue(self::MODE)->contrast();

        return $this;
    }

    /**
     * Use the palette of colors.
     *
     * @param array|Palette $colors The palette of colors
     *
     * @return AutoBackground
     */
    public function palette(...$colors)
    {
        $this->value->setSimpleNamedValue(self::PALETTE, ClassUtils::verifyVarArgsInstance($colors, Palette::class));

        return $this;
    }

}
