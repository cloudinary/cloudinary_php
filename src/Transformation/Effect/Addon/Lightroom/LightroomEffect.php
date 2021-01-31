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
 * Class LightroomEffect
 */
class LightroomEffect extends EffectAction implements LightroomEffectInterface
{
    const     LIGHTROOM = 'lightroom';

    const     SHARPNESS_RANGE            = [0, 150];
    const     SHARPEN_EDGE_MASKING_RANGE = [0, 10];
    const     EXPOSURE_RANGE             = [-5.0, 5.0];
    const     SHARPEN_RADIUS_RANGE       = [0.5, 3.0];

    use LightroomEffectTrait;

    /**
     * @param $name
     * @param $value
     * @param $range
     *
     * @return static
     */
    protected function addLightroomFilter($name, $value, $range = null)
    {
        $this->qualifiers[EffectQualifier::getName()]->addLightroomFilter($name, $value, $range);

        return $this;
    }

    /**
     * Lightroom XMP file.
     *
     * @param string $source The XMP file source (public ID).
     * @param string $value
     *
     * @return static
     */
    public function xmp($source)
    {
        $this->qualifiers[EffectQualifier::getName()]->xmp($source);

        return $this;
    }
}
