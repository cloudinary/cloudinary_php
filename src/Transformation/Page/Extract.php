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
 * Represents one or more pages of a paged document, such as a PDF or TIFF file.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/paged_and_layered_media#delivering_content_from_pdf_files
 * target="_blank">Delivering content from PDF files</a>
 *
 * @api
 */
abstract class Extract
{
    /**
     * Gets selected pages of a PDF/DOC.
     *
     * @param Page|PageQualifier|int ...$pages
     *
     * @return Page
     *
     * @internal
     */
    public static function getPage(...$pages)
    {
        return ClassUtils::forceVarArgsInstance($pages, Page::class);
    }

    /**
     * Gets selected frame of an animated image.
     *
     * @param Frame|PageQualifier|int ...$frames
     *
     * @return Page
     */
    public static function getFrame(...$frames)
    {
        return static::getPage($frames);
    }
}
