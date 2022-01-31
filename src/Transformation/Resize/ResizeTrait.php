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
 * Trait ImageResizeTrait
 *
 * @api
 */
trait ResizeTrait
{
    use GenericResizeTrait;
    use ScaleTrait;
    use PadTrait;
    use FillTrait;
    use FillPadTrait;
    use CropTrait;
    use ImaggaTrait;
}
