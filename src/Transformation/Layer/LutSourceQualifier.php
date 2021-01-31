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
 * Class LutSourceQualifier
 */
class LutSourceQualifier extends BaseSourceQualifier
{
    /**
     * @var string $sourceType The type of the layer.
     */
    protected $sourceType = 'lut';

    /**
     * LutLayerQualifier constructor.
     *
     * @param $lutId
     */
    public function __construct($lutId)
    {
        parent::__construct();

        $this->lut($lutId);
    }

    /**
     * Sets the lut source.
     *
     * @param string|SourceValue $lutId The public ID of the LUT asset.
     *
     * @return $this
     */
    public function lut($lutId)
    {
        $this->value->setValue(ClassUtils::verifyInstance($lutId, SourceValue::class));

        return $this;
    }
}
