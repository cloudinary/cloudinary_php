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
 * Represents one or more pages of a paged document, such as a PDF or TIFF file.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/paged_and_layered_media#delivering_content_from_pdf_files
 * target="_blank">Delivering content from PDF files</a>
 *
 * @api
 */
abstract class Page
{
    use PageNumberTrait;
    use PageRangeTrait;
    use PageAllTrait;


    /**
     * Internal named constructor.
     *
     * @param $value
     *
     * @return PageParam
     *
     * @internal
     */
    public static function createWithPageParam(...$value)
    {
        return new PageParam(...$value);
    }
}
