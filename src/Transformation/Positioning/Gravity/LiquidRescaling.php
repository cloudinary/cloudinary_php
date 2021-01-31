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
 * Class LiquidRescale
 *
 * Enables content-aware liquid rescaling (also sometimes known as 'seam carving'), which can be useful when changing
 * the aspect ratio of an image. Normal scaling retains all image content even when aspect ratios change, so important
 * elements of an image can be distorted. Liquid rescaling intelligently removes or duplicates 'seams' of pixels that
 * may zig zag horizontally or vertically through the picture. The seams are determined using an algorithm that selects
 * pixels with the least importance (least color change on either side of the seam). The result is an image where the
 * most 'important' elements of the image are retained and generally do not appear distorted although the relative
 * height or width of items in an image may change, especially if you significantly change the aspect ratio.
 * Tips and guidelines:
 * - This gravity can be used only in conjunction with c_scale (the default crop method).
 * - The liquid gravity works best when applied to scenic images with large 'unbusy' sections such as sky, grass, or
 * water.
 * - It also works best when applied to larger images. Thus, it is recommended to use this gravity to change aspect
 * ratio using relative widths and heights, where one of the two dimensions remains at or close to 1.0. If you also
 * want to resize the image, apply the resize on a different component of a chained transformation.
 * - In some cases, over-aggressive liquid rescaling can result in significant artifacts.
 * - Example: w_1.0,ar_1.0,g_liquid/w_500,h_500 uses liquid scaling to change an image to a square (aspect ratio of
 * 1:1) based on the original image width, and then resizes the result to 500x500.
 */
class LiquidRescaling extends GravityQualifier
{
    const LIQUID = 'liquid';

    /**
     * LiquidRescaling constructor.
     */
    public function __construct()
    {
        parent::__construct(self::LIQUID);
    }
}
