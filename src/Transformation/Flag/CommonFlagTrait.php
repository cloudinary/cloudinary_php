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
 * Trait LayerFlagTrait
 *
 * @api
 */
trait CommonFlagTrait
{
    /**
     * Delivers the image as an attachment.
     *
     * When the image's URL is accessed, tells the browser to save the image instead of embedding it in a page. You can
     * optionally set the attachment's filename. If you don't specify a filename, then the original image’s filename
     * will be used as the attachment filename (rather than the public_id) unless the discard_original_filename
     * qualifier was set during the file upload.
     *
     * @param string $filename The attachment's filename
     *
     * @return FlagQualifier
     */
    public static function attachment($filename = null)
    {
        return new FlagQualifier(self::ATTACHMENT, $filename);
    }

    /**
     * Allows specifying only either width or height so the value of the second axis remains as is, and is not
     * recalculated to maintain the aspect ratio of the original image.
     *
     * @return FlagQualifier
     */
    public static function ignoreAspectRatio()
    {
        return new FlagQualifier(self::IGNORE_ASPECT_RATIO);
    }

    /**
     * Adds ICC color space metadata to the image, even when the original image doesn't contain any ICC data.
     *
     * @return FlagQualifier
     */
    public static function forceIcc()
    {
        return new FlagQualifier(self::FORCE_ICC);
    }

    /**
     * Instructs Cloudinary to clear all image meta-data (IPTC, Exif and XMP) while applying an incoming transformation.
     *
     * @return FlagQualifier
     */
    public static function forceStrip()
    {
        return new FlagQualifier(self::FORCE_STRIP);
    }

    /**
     * Returns metadata of the input asset and of the transformed output asset in JSON instead of the transformed image.
     *
     * When used with g_auto, the metadata includes the proposed g_auto cropping coordinates.
     *
     * @return FlagQualifier
     */
    public static function getInfo()
    {
        return new FlagQualifier(self::GET_INFO);
    }

    /**
     * Sets the cache-control to immutable for the asset, which tells the browser that the asset does not have to be
     * revalidated with the server when the page is refreshed, and can be loaded directly from the cache.
     *
     * Currently supported only by Firefox.
     *
     * @return FlagQualifier
     */
    public static function immutableCache()
    {
        return new FlagQualifier(self::IMMUTABLE_CACHE);
    }

    /**
     * Keeps the copyright related fields when stripping meta-data. Without this flag, Cloudinary's default behavior is
     * to strip all meta-data when generating new image transformations.
     *
     * @return FlagQualifier
     */
    public static function keepAttribution()
    {
        return new FlagQualifier(self::KEEP_ATTRIBUTION);
    }

    /**
     * Keeps all meta-data. Without this flag, Cloudinary's default behavior is to strip all meta-data when generating
     * new image transformations.
     *
     * Note that this flag cannot be used in conjunction with the automatic quality transformation (q_auto).
     *
     * @return FlagQualifier
     */
    public static function keepIptc()
    {
        return new FlagQualifier(self::KEEP_IPTC);
    }

    /**
     * Sets generic flag specified by name.
     *
     * @param string $name    The flag name.
     * @param mixed  ...$args The flag value.
     *
     * @return FlagQualifier
     */
    public static function generic($name, ...$args)
    {
        return new FlagQualifier($name, ...$args);
    }
}
