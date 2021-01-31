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
 * Class SourceBasedEffectAction
 *
 * This is a base class for all source based effects.
 *
 * @internal
 */
abstract class SourceBasedEffectAction extends EffectAction
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
     * @param EffectQualifier|string $effect   The effect name.
     * @param BaseSource|string      $source   The source.
     * @param Position               $position Layer position.
     */
    public function __construct($effect, $source = null, $position = null)
    {
        parent::__construct($effect);

        $this->source($source);
        $this->position($position);
    }

    /**
     * Sets the source.
     *
     * @param BaseSource|string $source The source.
     *
     * @return static
     */
    public function source($source)
    {
        $this->source = ClassUtils::verifyInstance($source, BaseSource::class, ImageSource::class);

        return $this;
    }

    /**
     * Sets the position of the layer.
     *
     * @param BasePosition $position The position.
     *
     * @return static
     */
    public function position($position = null)
    {
        $this->position = ClassUtils::verifyInstance($position, BasePosition::class, AbsolutePosition::class);

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
        return ArrayUtils::implodeActionQualifiers(parent::__toString(), $this->source, $this->position);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'source'   => $this->source,
            'position' => $this->position,
        ];
    }
}
