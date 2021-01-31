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
use Cloudinary\Transformation\Argument\LimitedGenericNamedArgument;

/**
 * Class LightroomEffectQualifier
 *
 * This class is used for Adobe Photoshop Lightroom add-on.
 *
 * @internal
 */
class LightroomEffectQualifier extends ValueEffectQualifier
{
    use LightroomEffectTrait;

    /**
     * LightroomEffectQualifier constructor.
     */
    public function __construct()
    {
        parent::__construct(LightroomEffect::LIGHTROOM);
    }

    /**
     * @param $name
     * @param $value
     * @param $range
     *
     * @return static
     */
    public function addLightroomFilter($name, $value, $range = null)
    {
        return $this->add(new LimitedGenericNamedArgument($name, $value, $range));
    }

    /**
     * Lightroom XMP file.
     *
     * @param string $source The XMP file source (public ID).
     *
     * @return static
     */
    public function xmp($source)
    {
        return $this->setEffectValue(ClassUtils::verifyInstance($source, XmpSourceValue::class));
    }
}
