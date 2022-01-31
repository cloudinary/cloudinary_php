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
 * Controls the quality of the delivered image or video.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_optimization#how_to_optimize_image_quality
 * target="_blank">Image quality</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#quality_control
 * target="_blank">Video quality</a>
 *
 * @api
 */
class Quality extends BaseAction
{
    use QualityTrait;
    use QualityBuilderTrait;

    /**
     * Quality constructor.
     *
     * @param       $level
     * @param mixed ...$values
     */
    public function __construct($level, ...$values)
    {
        parent::__construct(new QualityQualifier($level, ...$values));
    }

    /**
     * When used together with automatic quality (q_auto):
     * allow switching to PNG8 encoding if the quality algorithm decides that it's more efficient.
     *
     * @return Quality
     *
     * @see Flag::anyFormat
     */
    public function anyFormat()
    {
        return $this->setFlag(Flag::anyFormat());
    }

    /**
     * Named Quality constructor.
     *
     * @param int|string $level  The quality level.
     * @param array      $values Additional arguments.
     *
     * @return Quality
     */
    protected static function createQuality($level, ...$values)
    {
        return new self($level, ...$values);
    }

    /**
     * Sets simple  value.
     *
     * @param string             $name
     * @param BaseComponent|null $value
     *
     * @return $this
     *
     * @internal
     */
    protected function setSimpleValue($name, $value = null)
    {
        $this->qualifiers[QualityQualifier::getName()]->setSimpleValue($name, $value);

        return $this;
    }

    /**
     * Sets the simple named value of the quality qualifier.
     *
     * @param string             $name  The named argument name
     * @param BaseComponent|null $value The value
     *
     * @return $this
     *
     * @internal
     */
    protected function setSimpleNamedValue($name, $value = null)
    {
        $this->qualifiers[QualityQualifier::getName()]->setSimpleNamedValue($name, $value);

        return $this;
    }
}
