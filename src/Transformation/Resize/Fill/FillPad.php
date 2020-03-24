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

use InvalidArgumentException;

/**
 * Class FillPad
 *
 * @internal
 */
class FillPad extends Fill
{
    use FillPadTrait;
    use BackgroundTrait;

    /**
     * FillPad constructor.
     *
     * @param      $cropMode
     * @param null $width
     * @param null $height
     * @param null $gravity
     * @param null $background
     */
    public function __construct($cropMode, $width = null, $height = null, $gravity = null, $background = null)
    {
        if ($gravity === null) {
            $gravity = Gravity::auto();
        }

        parent::__construct($cropMode, $width, $height, $gravity);

        $this->background($background);
    }

    /**
     * Sets the gravity to use when using the FILL_PAD crop mode.
     *
     * @param $autoGravity
     *
     * @return $this
     */
    public function gravity($autoGravity)
    {
        if (! $autoGravity instanceof FocalGravity) {
            throw new InvalidArgumentException('FillPad only supports Auto Gravity');
        }

        $this->addParameter($autoGravity);

        return $this;
    }
}
