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
 * Trait VideoTransformationTrait
 *
 * @api
 */
trait VideoTransformationTrait
{
    use CommonTransformationTrait;
    use VideoSpecificTransformationTrait;
}
