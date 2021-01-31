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
 * Defines how to improve an image by automatically adjusting image colors, contrast and brightness.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#image_improvement_effects target="_blank">
 * Image improvement effects</a>
 *
 * @api
 */
class Improve extends BlendEffectQualifier
{
    use EffectModeTrait;

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'mode', 'value'];

    /**
     * Improve constructor.
     *
     * @param      $blend
     * @param null $mode
     */
    public function __construct($blend = null, $mode = null)
    {
        parent::__construct(Adjust::IMPROVE, EffectRange::PERCENT, $blend);

        $this->mode($mode);
    }

    /**
     * Sets the improve effect to INDOOR mode.
     *
     * Use this mode to get better results on images with indoor lighting and shadows.
     *
     * @param int $blend How much to blend the improved result with the original image, where 0 means only use the
     *                   original and 100 means only use the improved result.
     *
     * @return Improve
     */
    public static function indoor($blend = null)
    {
        return new static($blend, ImproveMode::INDOOR);
    }

    /**
     * Sets the improve effect to OUTDOOR mode.
     *
     * @param int $blend How much to blend the improved result with the original image, where 0 means only use the
     *                   original and 100 means only use the improved result.
     *
     * @return Improve
     */
    public static function outdoor($blend = null)
    {
        return new static($blend, ImproveMode::OUTDOOR);
    }
}
