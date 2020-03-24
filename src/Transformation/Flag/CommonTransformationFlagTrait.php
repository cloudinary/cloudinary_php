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

/**
 * Trait TransformationFlagTrait
 *
 * @api
 */
trait CommonTransformationFlagTrait
{
    /**
     * Delivers the image as an attachment.
     *
     * @param string $filename The attachment's filename
     *
     * @return static
     *
     * @see Flag::attachment
     */
    public function attachment($filename = null)
    {
        return $this->addAction(Flag::attachment($filename));
    }

    /**
     * Adds ICC color space metadata to the image, even when the original image doesn't contain any ICC data.
     *
     * @return static
     *
     * @see Flag::forceIcc
     */
    public function forceIcc()
    {
        return $this->addAction(Flag::forceIcc());
    }

    /**
     * Instructs Cloudinary to clear all image meta-data (IPTC, Exif and XMP) while applying an incoming transformation.
     *
     * @return static
     *
     * @see Flag::forceStrip
     */
    public function forceStrip()
    {
        return $this->addAction(Flag::forceStrip());
    }

    /**
     * Returns metadata of the input asset and of the transformed output asset in JSON instead of the transformed image.
     *
     * @return static
     *
     * @see Flag::getInfo
     */
    public function getInfo()
    {
        return $this->addAction(Flag::getInfo());
    }

    /**
     * Sets the cache-control to immutable for the asset.
     *
     * @return static
     *
     * @see Flag::immutableCache
     */
    public function immutableCache()
    {
        return $this->addAction(Flag::immutableCache());
    }

    /**
     * Keeps the copyright related fields when stripping meta-data.
     *
     * @return static
     *
     * @see Flag::keepAttribution
     */
    public function keepAttribution()
    {
        return $this->addAction(Flag::keepAttribution());
    }

    /**
     * Keeps all meta-data.
     *
     * @return static
     *
     * @see Flag::keepIptc
     */
    public function keepIptc()
    {
        return $this->addAction(Flag::keepIptc());
    }

    /**
     * Instructs Cloudinary to clear all ICC color profile data included with the image.
     * @return static
     *
     * @see Flag::stripProfile
     */
    public function stripProfile()
    {
        return $this->addAction(Flag::stripProfile());
    }
}
