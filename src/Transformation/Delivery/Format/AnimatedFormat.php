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
 * Defines the animated format of the delivered asset.
 *
 * @api
 */
class AnimatedFormat extends BaseAction
{
    use AnimatedFormatTrait;

    /**
     * Format constructor.
     *
     * @param array $value
     */
    public function __construct(...$value)
    {
        parent::__construct(ClassUtils::verifyVarArgsInstance($value, FormatQualifier::class));

        $this->setFlag(Flag::animated());
    }

    /**
     * Sets file format.
     *
     * @param string $format The file format.
     *
     * @return AnimatedFormat
     */
    public function format($format)
    {
        $this->qualifiers[FormatQualifier::getName()]->format($format);

        return $this;
    }

    /**
     * Automatically use lossy compression when delivering animated GIF files.
     *
     * @param bool $useLossy Indicates whether to use lossy compression.
     *
     * @return AnimatedFormat
     *
     * @see Flag::lossy
     */
    public function lossy($useLossy = true)
    {
        return $this->setFlag(Flag::lossy(), $useLossy);
    }
}
