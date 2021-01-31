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
 * Class OutlineQualifier
 *
 */
class OutlineQualifier extends EffectQualifier
{
    protected $valueOrder = [0, 'mode', 'width', 'blur_level'];

    public function __construct(...$values)
    {
        parent::__construct(MiscEffect::OUTLINE, $values);
    }

    /**
     * Sets the outline mode.
     *
     * @param string $mode The outline mode.
     *
     * @return OutlineQualifier
     */
    public function mode($mode)
    {
        $this->value->setSimpleValue('mode', $mode);

        return $this;
    }
    /**
     * Sets the outline width.
     *
     * @param int|string $width The width in pixels.
     *
     * @return OutlineQualifier
     */
    public function width($width)
    {
        $this->value->setSimpleValue('width', $width);

        return $this;
    }

    /**
     * Sets the outline blur level.
     *
     * @param int|string $blurLevel The level of blur.
     *
     * @return OutlineQualifier
     */
    public function blurLevel($blurLevel)
    {
        $this->value->setSimpleValue('blur_level', $blurLevel);

        return $this;
    }
}
