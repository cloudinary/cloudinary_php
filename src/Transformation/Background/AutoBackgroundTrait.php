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

use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait BackgroundTrait
 *
 * @api
 */
trait AutoBackgroundTrait
{
    /**
     * Selects the predominant color while taking only the image border pixels into account.
     *
     * @return AutoBackground
     */
    public static function border()
    {
        return new AutoBackground(AutoBackground::BORDER);
    }

    /**
     * Selects the predominant color while taking all pixels in the image into account.
     *
     * @return AutoBackground
     */
    public static function predominant()
    {
        return new AutoBackground(AutoBackground::PREDOMINANT);
    }
}
