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
    /**
     * Outline constructor.
     *
     * @param $mode
     * @param $width
     * @param $blurLevel
     */
    public function __construct($mode = null, $width = null, $blurLevel = null)
    {
        parent::__construct(new OutlineQualifier());

        $this->mode($mode);
        $this->width($width);
        $this->blurLevel($blurLevel);
    }

    /**
     * Sets the outline mode.
     *
     * @param int|string $mode The outline mode.
     *
     * @return Outline
     */
    public function mode($mode)
    {
        $this->qualifiers[OutlineQualifier::getName()]->mode($mode);

        return $this;
    }

    /**
     * Sets the outline width.
     *
     * @param int|string $width The width in pixels.
     *
     * @return Outline
     */
    public function width($width)
    {
        $this->qualifiers[OutlineQualifier::getName()]->width($width);

        return $this;
    }

    /**
     * Sets the outline blur level.
     *
     * @param int|string $blurLevel The level of blur.
     *
     * @return Outline
     */
    public function blurLevel($blurLevel)
    {
        $this->qualifiers[OutlineQualifier::getName()]->blurLevel($blurLevel);

        return $this;
    }
}
