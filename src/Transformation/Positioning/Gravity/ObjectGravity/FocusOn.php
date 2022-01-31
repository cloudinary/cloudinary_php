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
 * Defines the objects that can be focused on.
 *
 * @api
 */
abstract class FocusOn implements FocalGravityInterface, ObjectGravityInterface
{
    use ObjectGravityTrait;
    use FocalGravityBuilderTrait;
}
