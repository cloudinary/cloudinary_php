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
class Improve extends LimitedEffectParam
{
    use EffectModeTrait;

    /**
     * INDOOR mode. Use this mode to get better results on images with indoor lighting and shadows.
     */
    const INDOOR = 'indoor';

    /**
     * OUTDOOR mode (Server default).
     */
    const OUTDOOR = 'outdoor';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'mode', 'value'];

    /**
     * Improve constructor.
     *
     * @param      $strength
     * @param null $mode
     */
    public function __construct($strength = null, $mode = null)
    {
        parent::__construct(Adjust::IMPROVE, EffectRange::PERCENT, $strength);

        $this->mode($mode);
    }

    /**
     * Sets the improve effect to INDOOR mode.
     *
     * Use this mode to get better results on images with indoor lighting and shadows.
     *
     * @param int $strength           How much to blend the improved result with the original image,
     *                                where 0 means only use the original and 100 means only use the improved result.
     *
     * @return Improve
     */
    public static function indoor($strength = null)
    {
        return new static($strength, self::INDOOR);
    }

    /**
     * Sets the improve effect to OUTDOOR mode.
     *
     * @param int $strength           How much to blend the improved result with the original image,
     *                                where 0 means only use the original and 100 means only use the improved result.
     *
     * @return Improve
     */
    public static function outdoor($strength = null)
    {
        return new static($strength, self::OUTDOOR);
    }
}
