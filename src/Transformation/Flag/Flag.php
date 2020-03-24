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
 * Class Flag
 *
 * @api
 */
abstract class Flag implements CommonFlagInterface, LayerFlagInterface, ImageFlagInterface, VideoFlagInterface
{
    use CommonFlagTrait;
    use ImageFlagTrait;
    use LayerFlagTrait;
    use VideoFlagTrait;
}
