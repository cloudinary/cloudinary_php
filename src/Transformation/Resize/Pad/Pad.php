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
 * Class BasePad
 */
class Pad extends BaseResizeAction
{
    use PadTrait;

    /**
     * Pad constructor.
     *
     * @param      $cropMode
     * @param null $width
     * @param null $height
     * @param null $background
     */
    public function __construct(
        $cropMode,
        $width = null,
        $height = null,
        $background = null
    ) {
        parent::__construct($cropMode, $width, $height);

        $this->background($background);
    }
}
