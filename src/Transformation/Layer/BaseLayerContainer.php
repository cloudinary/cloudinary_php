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
use Cloudinary\ClassUtils;

/**
 * Class BaseLayerContainer
 *
 * This is a base class for all layer containers (overlays/underlays).
 *
 * @internal
 */
abstract class BaseLayerContainer extends BaseAction
{
    /**
     * @var BaseSource $source The source of the layer.
     */
    protected $source;

    /**
     * @var Position $position Layer position.
     */
    protected $position;

    /**
     * BaseLayerContainer constructor.
     *
     * @param BaseSource|string $source    The source.
     * @param Position          $position  Layer position.
     */
    public function __construct($source = null, $position = null)
    {
        parent::__construct();

        $this->source($source);
        $this->position($position);
    }

    /**
     * Sets the source.
     *
     * @param BaseSource $source The source.
     *
     * @return static
     */
    abstract public function source($source);

    /**
     * Sets the layer position.
     *
     * @param Position $position The Position of the layer.
     *
     * @return static
     */
    abstract public function position($position = null);

    /**
     * Sets stack position of the layer.
     *
     * @param string $stackPosition The stack position.
     *
     * @return $this
     */
    public function setStackPosition($stackPosition)
    {
        $this->source->setStackPosition($stackPosition);

        return $this;
    }

    /**
     * Serializes to Cloudinary URL format
     *
     * @return string
     */
    public function __toString()
    {
        // We actually combine components...
        return ArrayUtils::implodeActionQualifiers($this->source, $this->position, parent::__toString());
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'source'     => $this->source,
            'position'   => $this->position,
        ];
    }
}
