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

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class BaseSourceQualifier
 */
class BaseSourceQualifier extends BaseQualifier
{
    /**
     * @var string $name The name of the source qualifier.
     */
    protected static $name = 'source';

    /**
     * @var string $sourceType The type of the source.
     */
    protected $sourceType;

    /**
     * @var string The stack position of the layer.
     */
    protected $stackPosition = LayerStackPosition::OVERLAY;

    /**
     * Gets the source key.
     *
     * Key depends on the stack position.
     *
     * @return string
     *
     * @internal
     */
    public function getSourceKey()
    {
        if ($this->stackPosition === LayerStackPosition::UNDERLAY) {
            return 'u';
        }

        return 'l';
    }

    /**
     * Gets component full name.
     *
     * @return string Component name.
     */
    public function getFullName()
    {
        return ArrayUtils::implodeFiltered('_', [parent::getFullName(), $this->stackPosition]);
    }

    /**
     * Sets stack position of the source.
     *
     * @param string $stackPosition The stack position.
     *
     * @return $this
     */
    public function setStackPosition($stackPosition)
    {
        $this->stackPosition = $stackPosition;

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $sourceTypeStr = $this->sourceType ? "$this->sourceType:" : '';

        return empty((string)$this->value) ? '' : "{$this->getSourceKey()}_{$sourceTypeStr}{$this->value}";
    }
}
