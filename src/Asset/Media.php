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

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\TransformationTrait;

/**
 * Class Media
 *
 * @api
 */
class Media extends BaseMediaAsset
{
    use TransformationTrait;

    /**
     * Internal method that returns the asset type of the current object.
     *
     * @param string|BaseAsset $class The instance of the object.
     *
     *
     * @internal
     */
    protected static function getAssetType(BaseAsset|string $class): string
    {
        if (isset(static::$assetType)) {
            return static::$assetType;
        }

        // Default asset type
        return AssetType::IMAGE;
    }

    /**
     * Gets the transformation.
     *
     */
    public function getTransformation(): Transformation|CommonTransformation
    {
        if (! isset($this->transformation)) {
            $this->transformation = new Transformation();
        }

        return $this->transformation;
    }

    /**
     * Internal getter for a list of the delivery types that support SEO suffix.
     *
     *
     * @internal
     */
    public static function getSuffixSupportedDeliveryTypes(): array
    {
        if (empty(self::$suffixSupportedDeliveryTypes)) {
            self::$suffixSupportedDeliveryTypes = ArrayUtils::mergeNonEmpty(
                Image::getSuffixSupportedDeliveryTypes(),
                Video::getSuffixSupportedDeliveryTypes(),
                File::getSuffixSupportedDeliveryTypes()
            );
        }

        return self::$suffixSupportedDeliveryTypes;
    }

    /**
     * Finalizes the asset type.
     *
     */
    protected function finalizeAssetType(): ?string
    {
        return $this->finalizeShorten(parent::finalizeAssetType());
    }
}
