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

/**
 * Trait AssetDescriptorTrait
 *
 */
trait AssetDescriptorTrait
{
    /**
     * Sets the type of the asset.
     *
     * @param string $assetType The type of the asset, A.K.A resource type.
     *                          Use the constants defined in the AssetType class.
     *
     * @return $this
     *
     * @see                   AssetType
     */
    public function assetType(string $assetType): static
    {
        return $this->setAssetProperty('assetType', $assetType);
    }

    /**
     * Sets the delivery type of the asset.
     *
     * @param string $deliveryType The delivery type of the asset, A.K.A type.
     *                             Use the constants defined in the DeliveryType class.
     *
     * @return $this
     *
     * @see DeliveryType
     */
    public function deliveryType(string $deliveryType): static
    {
        return $this->setAssetProperty('deliveryType', $deliveryType);
    }

    /**
     * Sets the asset version.
     *
     * @param int|string $version Asset version, typically set to unix timestamp.
     *
     * @return $this
     */
    public function version(int|string $version): static
    {
        return $this->setAssetProperty('version', $version);
    }

    /**
     * Sets the asset location.
     *
     * @param string $location Can be directory, URL(including path, excluding filename), etc.
     *
     * @return $this
     */
    public function location(string $location): static
    {
        return $this->setAssetProperty('location', $location);
    }

    /**
     * Sets the filename of the asset.
     *
     * @param string $filename Basename without extension
     *
     * @return $this
     */
    public function filename(string $filename): static
    {
        return $this->setAssetProperty('filename', $filename);
    }

    /**
     * Sets the file extension of the asset.
     *
     * @param string $extension A.K.A format
     *
     * @return $this
     */
    public function extension(string $extension): static
    {
        return $this->setAssetProperty('extension', $extension);
    }

    /**
     * Sets SEO URL suffix.
     *
     * @param string $suffix The SEO URL suffix.
     *
     * @return $this
     */
    public function suffix(string $suffix): static
    {
        return $this->setAssetProperty('suffix', $suffix);
    }

    /**
     * Sets the property of the asset descriptor.
     *
     * @param string $propertyName  The name of the property.
     * @param mixed  $propertyValue The value of the property.
     *
     * @return $this
     *
     * @internal
     */
    abstract public function setAssetProperty(string $propertyName, mixed $propertyValue): static;
}
