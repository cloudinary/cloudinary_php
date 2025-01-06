<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\VideoTransformation;
use Cloudinary\Transformation\VideoTransformationInterface;
use Cloudinary\Transformation\VideoTransformationTrait;

/**
 * Class Video
 *
 * @api
 */
class Video extends BaseMediaAsset implements VideoTransformationInterface
{
    use VideoTransformationTrait;

    /**
     * @var array A list of the delivery types that support SEO suffix.
     */
    protected static array $suffixSupportedDeliveryTypes = [
        AssetType::VIDEO => [DeliveryType::UPLOAD => 'videos'],
    ];

    /**
     * Gets the transformation.
     *
     */
    public function getTransformation(): CommonTransformation|VideoTransformation
    {
        if (! isset($this->transformation)) {
            $this->transformation = new VideoTransformation();
        }

        return $this->transformation;
    }
}
