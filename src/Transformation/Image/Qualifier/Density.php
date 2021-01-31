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
 * Class Density
 *
 * Controls the density to use when delivering an image or when converting a vector file such as a PDF or EPS document
 * to a web image delivery format.
 *
 * - For web image formats: By default, Cloudinary normalizes derived image for web optimization purposes and delivers
 * them at 72 dpi. You can use the density qualifier to control the dpi. This can be useful when generating a derived
 * image intended for printing.
 * Tip: You can take advantage of the idn (initial density) value to automatically set the
 * density of your image to the (pre-normalized) initial density of the original image Density::INITIAL_DENSITY.
 * This value is taken from the original image's metadata.
 *
 *  - For vector files: PDF, EPS, etc.: When you deliver a vector file in a web image format, it is delivered by
 *  default at 150 dpi. Supported range: 1-300.
 */
class Density extends BaseQualifier
{
    /**
     * @var string INITIAL_DENSITY Pre-normalized initial density of the original image.
     */
    const INITIAL_DENSITY = 'idn';

    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'dn';
}
