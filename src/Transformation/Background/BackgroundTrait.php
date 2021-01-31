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
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait BackgroundTrait
 *
 * @api
 */
trait BackgroundTrait
{
    /**
     * Sets the background.
     *
     * The image background is visible when padding is added with one of the padding crop modes, when rounding corners,
     * when adding overlays, and with semi-transparent PNGs and GIFs.
     *
     * @param Background|ColorValue|string $background The the background to set.
     *
     * @return $this
     *
     * @see Background
     */
    public function background($background)
    {
        $this->addQualifier(ClassUtils::verifyInstance($background, Background::class));

        return $this;
    }
}
