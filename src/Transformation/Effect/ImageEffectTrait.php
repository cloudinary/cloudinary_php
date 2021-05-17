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
 * Trait ImageEffectTrait
 *
 * @api
 */
trait ImageEffectTrait
{
    use ImageColorEffectTrait;
    use ImagePixelEffectTrait;
    use MiscEffectTrait;
    use AddonEffectTrait;
    use ThemeEffectTrait;
}
