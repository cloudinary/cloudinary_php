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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class CropMode
 *
 * Represents a crop mode qualifier (c_ in the URL).
 *
 * @internal
 */
class CropMode extends BaseQualifier
{
    use CropModeTrait;

    /**
     * @var string $name The name of the CropMode qualifier.
     */
    protected static $name = 'crop_mode';

    /**
     * @var string $key The key of the CropMode qualifier.
     */
    protected static $key = 'c';

    /**
     * The SCALE crop mode changes the size of the asset exactly to the given width and height without necessarily
     * retaining the original aspect ratio. All original parts are visible but might be stretched or shrunk.
     */
    const SCALE = 'scale';

    /**
     * The FIT crop mode resizes the asset so that it takes up as much space as possible within a bounding box defined
     * by the given width and height qualifiers. The original aspect ratio is retained and all of the original asset
     * is visible.
     */
    const FIT = 'fit';

    /**
     * The LIMIT_FIT crop mode resizes the asset so that it takes up as much space as possible within a bounding box
     * defined by the given width and height qualifiers, but only if the original asset is larger than the given limit
     * (width and height). The asset is scaled down, the original aspect ratio is retained and all of the original
     * asset is visible.
     */
    const LIMIT_FIT = 'limit';

    /**
     * The MINIMUM_FIT crop mode resizes the asset so that it takes up as much space as possible within a bounding box
     * defined by the given width and height qualifiers, but only if the original asset is smaller than the given
     * minimum (width and height). The asset is scaled up, the original aspect ratio is retained and all of the
     * original asset is visible.
     */
    const MINIMUM_FIT = 'mfit';

    /**
     * The PAD crop mode resizes the asset to fill the given width and height while retaining the original aspect ratio.
     * If the proportions of the original asset do not match the given width and height, padding is added to the asset
     * to reach the required size.
     */
    const PAD = 'pad';

    /**
     * The LIMIT_PAD crop mode resizes the asset to fill the given width and height while retaining the original aspect
     * ratio, but only if the original asset is larger than the given limit (width and height). The asset is scaled
     * down.  If the proportions of the original asset do not match the given width and height, padding is added to the
     * asset to reach the required size.
     */
    const LIMIT_PAD = 'lpad';

    /**
     * The MINIMUM_PAD crop mode resizes the asset to fill the given width and height while retaining the original
     * aspect ratio, but only if the original asset is smaller than the given minimum (width and height). The asset is
     * scaled up.  If the proportions of the original asset do not match the given width and height, padding is added
     * to the asset to reach the required size.
     */
    const MINIMUM_PAD = 'mpad';

    /**
     * The FILL crop mode creates an asset with the exact given width and height without distorting the asset. This
     * option first scales as much as needed to at least fill both of the given dimensions. If the requested aspect
     * ratio is different than the original, cropping will occur on the dimension that exceeds the requested size after
     * scaling.
     */
    const FILL = 'fill';

    /**
     * The LIMIT_FILL crop mode creates an asset with the exact given width and height without distorting the asset,
     * but only if the original asset is larger than the specified resolution limits. The asset is scaled down to fill
     * the given width and height without distorting the asset, and then the dimension that exceeds the request is
     * cropped. If the original dimensions are both smaller than the requested size, it is not resized at all.
     */
    const LIMIT_FILL = 'lfill';

    /**
     * The FILL_PAD crop mode tries to prevent a "bad crop" by first attempting to use the fill mode, but adds padding
     * if it is determined that more of the original asset needs to be included in the final asset. Only supported in
     * conjunction with automatic cropping (g_auto).
     */
    const FILL_PAD = 'fill_pad';

    /**
     * The CROP crop mode extracts a given width and height out of the original asset. The original proportions are
     * retained and so is the size of the graphics.
     */
    const CROP = 'crop';

    /**
     * The THUMBNAIL crop mode generates a thumbnail using face detection in combination with the 'face' or 'faces'
     * gravity.
     */
    const THUMBNAIL = 'thumb';

    /**
     * The IMAGGA_CROP crop mode crops your image based on automatically calculated areas of interest within each
     * specific photo.
     *
     * @see https://cloudinary.com/documentation/imagga_crop_and_scale_addon#smartly_crop_images
     */
    const IMAGGA_CROP = 'imagga_crop';

    /**
     * The IMAGGA_SCALE crop mode scales your image based on automatically calculated areas of interest within each
     * specific photo.
     *
     * @see https://cloudinary.com/documentation/imagga_crop_and_scale_addon#smartly_scale_images
     */
    const IMAGGA_SCALE = 'imagga_scale';
}
