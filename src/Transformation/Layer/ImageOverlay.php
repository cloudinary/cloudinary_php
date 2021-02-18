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
 * Class ImageOverlay
 */
class ImageOverlay extends BaseSourceContainer
{
    use ImageSourceTrait;

    /**
     * @var BlendMode $blendMode Layer blend mode.
     */
    protected $blendMode;

    /**
     * BaseLayerContainer constructor.
     *
     * @param BaseSource|string $source    The source.
     * @param Position          $position  Layer position.
     * @param string|BlendMode  $blendMode Layer blend mode.
     */
    public function __construct($source = null, $position = null, $blendMode = null)
    {
        parent::__construct($source, $position);
        $this->blendMode($blendMode);
    }

    /**
     * Sets layer blend mode.
     *
     * @param BlendMode $blendMode The blend mode.
     *
     * @return static
     */
    public function blendMode($blendMode = null)
    {
        $this->blendMode = ClassUtils::verifyInstance($blendMode, EffectQualifier::class, BlendMode::class);

        return $this;
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
     * @return array
     */
    protected function getSubActionQualifiers()
    {
        $subActionQualifiers = parent::getSubActionQualifiers();

        $subActionQualifiers['additional'] = ArrayUtils::mergeNonEmpty(
            $subActionQualifiers['additional'],
            $this->blendMode? $this->blendMode->getStringQualifiers(): []
        );

        return $subActionQualifiers;
    }
}
