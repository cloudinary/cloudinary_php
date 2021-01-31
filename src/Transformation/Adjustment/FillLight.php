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
 * Adjusts the fill light and blends the result with the original image.
 *
 * @api
 */
class FillLight extends BlendEffectQualifier
{
    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'value', 'bias'];

    /**
     * FillLight constructor.
     *
     * @param int $blend    How much to blend the adjusted fill light, where 0 means only use the original
     *                      and 100 means only use the adjusted fill light result.
     *                      (Range: 0 to 100, Server default: 100)
     * @param int $bias     The  bias to apply to the fill light effect (Range: -100 to 100, Server default: 0).
     */
    public function __construct($blend = null, $bias = null)
    {
        parent::__construct(Adjust::FILL_LIGHT, EffectRange::PERCENT, $blend);

        $this->bias($bias);
    }

    /**
     * Sets the bias.
     *
     * @param int $bias The bias to apply to the fill light effect (Range: -100 to 100, Server default: 0).
     *
     * @return FillLight
     */
    public function bias($bias)
    {
        $this->getValue()->setSimpleValue('bias', $bias);

        return $this;
    }
}
