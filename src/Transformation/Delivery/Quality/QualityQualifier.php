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

use Cloudinary\Transformation\Qualifier\BaseExpressionQualifier;

/**
 * Class QualityQualifier
 */
class QualityQualifier extends BaseExpressionQualifier
{
    use QualityTrait;
    use QualityBuilderTrait;

    /**
     * Automatically calculate the optimal quality for an image: the smallest file size without affecting its
     * perceptual quality (same as GOOD).
     */
    const AUTO = 'auto';

    /**
     * Automatically calculate the optimal quality for an image: the smallest file size without affecting its
     * perceptual quality.
     */
    const GOOD = 'good';

    /**
     * Automatically calculate the optimal quality for images using a less aggressive algorithm.
     */
    const BEST = 'best';

    /**
     * Automatically calculate the optimal quality for images using a more aggressive algorithm.
     */
    const ECO = 'eco';

    /**
     * Automatically calculate the optimal quality for images using the most aggressive algorithm.
     */
    const LOW = 'low';

    /**
     *  Significantly reduces the size of photographs without affecting their perceptual quality.
     */
    const JPEG_MINI = 'jpegmini';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'preset']; // FIXME: first item should be named!

    /**
     * Quality constructor.
     *
     * @param       $strength
     * @param null  $preset
     * @param array $values
     */
    public function __construct($strength, $preset = null, ...$values)
    {
        parent::__construct($strength);

        $this->setSimpleValue('preset', $preset);
        $this->value->addValues(...$values);
    }

    /**
     * Sets a simple unnamed value specified by name (for uniqueness) and the actual value.
     *
     * @param string              $name  The name of the argument.
     * @param BaseComponent|mixed $value The value of the argument.
     *
     * @return $this
     *
     * @internal
     */
    public function setSimpleValue($name, $value = null)
    {
        $this->value->setSimpleValue($name, $value);

        return $this;
    }

    /**
     * Sets the simple named value of the quality qualifier.
     *
     * @param string              $name  The named argument name.
     * @param BaseComponent|mixed $value The value.
     *
     * @return $this
     *
     * @internal
     */
    public function setSimpleNamedValue($name, $value = null)
    {
        $this->value->setSimpleNamedValue($name, $value);

        return $this;
    }
}
