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
 * Class VideoEffect
 *
 * @api
 */
abstract class VideoEffect
{
    use CommonEffectTrait;
    use VideoEffectTrait;
}
