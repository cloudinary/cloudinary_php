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
 * Class Outline
 */
class Outline extends ColoredEffectAction
{
    const INNER      = 'inner';
    const INNER_FILL = 'inner_fill';
    const OUTER      = 'outer';
    const FILL       = 'fill';

    /**
     * Outline constructor.
     *
     * @param $mode
     * @param $width
     * @param $blur
     */
    public function __construct($mode = null, $width = null, $blur = null)
    {
        parent::__construct(MiscEffect::OUTLINE, $mode, $width, $blur);
    }
}
