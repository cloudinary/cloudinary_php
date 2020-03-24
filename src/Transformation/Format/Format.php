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
 * Class Format
 *
 * @api
 */
class Format extends BaseAction implements FormatInterface
{
    use FormatTrait;

    /**
     * Format constructor.
     *
     * @param array $value
     */
    public function __construct(...$value)
    {
        parent::__construct(ClassUtils::verifyVarArgsInstance($value, FormatParam::class));
    }

    /**
     * Sets file format.
     *
     * @param string $format The file format.
     *
     * @return Format
     */
    public function format($format)
    {
        $this->parameters[FormatParam::getName()]->format($format);

        return $this;
    }

    /**
     * Automatically use lossy compression when delivering animated GIF files.
     *
     * @return Format
     *
     * @see Flag::lossy
     */
    public function lossy()
    {
        return $this->setFlag(Flag::lossy());
    }

    /**
     * Applicable only for JPG file format
     *
     * @param string $mode The mode to determine a specific progressive outcome.
     *
     * @return Format
     *
     * @see Flag::progressive
     */
    public function progressive($mode = null)
    {
        return $this->setFlag(Flag::progressive($mode));
    }

    /**
     * Ensures that images with a transparency channel will be delivered in PNG format.
     *
     * @return Format
     *
     * @see Flag::preserveTransparency
     */
    public function preserveTransparency()
    {
        return $this->setFlag(Flag::preserveTransparency());
    }
}
