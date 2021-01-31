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
 * Trait AddonEffectTrait
 *
 * @api
 */
trait AddonEffectTrait
{

    /**
     * Applies Adobe Lightroom filter.
     *
     * @return LightroomEffect
     */
    public static function lightroom()
    {
        return new LightroomEffect(new LightroomEffectQualifier());
    }
}
