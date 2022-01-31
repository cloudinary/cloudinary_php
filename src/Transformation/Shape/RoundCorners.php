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

/**
 * Round one or more corners of an image or video.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#rounding_corners_and_creating_circular_images
 * target="_blank">Rounded images</a> |
 * <a href=
 * https://cloudinary.com/documentation/video_manipulation_and_delivery#rounding_corners_and_creating_circular_videos
 * target="_blank">Rounded videos</a>
 *
 * @api
 */
class RoundCorners extends BaseAction
{
    use CornerRadiusTrait;
    use CornersTrait;

    /**
     * RoundCorners constructor.
     *
     * @param mixed ...$qualifiers
     */
    public function __construct(...$qualifiers)
    {
        parent::__construct(ClassUtils::verifyVarArgsInstance($qualifiers, CornerRadius::class));
    }

    /**
     * Sets the corner radius.
     *
     * @param int|string|array|mixed $values The corner(s) radius.
     *
     * @return static
     */
    public function setRadius(...$values)
    {
        return $this->addQualifier(ClassUtils::verifyVarArgsInstance($values, CornerRadius::class));
    }

    /**
     * Sets a simple unnamed value specified by name (for uniqueness) and the actual value.
     *
     * @param string              $name  The name of the argument.
     * @param BaseComponent|mixed $value The value of the argument.
     *
     * @return $this
     *
     * @internal
     */
    public function setSimpleValue($name, $value = null)
    {
        $this->qualifiers[CornerRadius::getName()]->setSimpleValue($name, $value);

        return $this;
    }

    /**
     * Internal named constructor.
     *
     * @param int|string|array|mixed $radius The corner(s) radius.
     *
     * @return static
     *
     * @internal
     */
    protected static function createWithRadius(...$radius)
    {
        return new static(CornerRadius::byRadius(...$radius));
    }
}
