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
 * Trait TransformationTrait
 *
 * @api
 */
trait TransformationTrait
{
    use CommonTransformationTrait;
    use ImageSpecificTransformationTrait;
    use VideoSpecificTransformationTrait {
        ImageSpecificTransformationTrait::roundCorners insteadof VideoSpecificTransformationTrait;

        ImageSpecificTransformationTrait::overlay insteadof VideoSpecificTransformationTrait;
        VideoSpecificTransformationTrait::overlay as videoOverlay;
    }
}
