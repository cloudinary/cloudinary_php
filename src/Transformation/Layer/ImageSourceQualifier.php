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
 * Class ImageSourceQualifier
 */
class ImageSourceQualifier extends BaseSourceQualifier
{
    use LayerSourceTrait;

    /**
     * @var string $sourceType The type of the layer.
     */
    protected $sourceType;

    /**
     * ImageSourceQualifier constructor.
     *
     * @param string|SourceValue|mixed $source The source of the layer
     */
    public function __construct($source)
    {
        parent::__construct(ClassUtils::verifyInstance($source, SourceValue::class));
    }
}
