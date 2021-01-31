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
 * Trait LayeredImageTransformationTrait
 *
 * @api
 */
trait LayeredImageTransformationTrait
{
    /**
     * Extracts selected pages/frames from the asset.
     *
     * @param PageQualifier|string|int ...$pages
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/paged_and_layered_media#deliver_a_pdf_or_selected_pages_of_a_pdf
     */
    public function extract(...$pages)
    {
        return $this->addAction(ClassUtils::verifyVarArgsInstance($pages, Page::class));
    }

    /**
     * Extracts selected layers/embedded objects from the PSD file.
     *
     * @param PageQualifier|string|int ...$pages
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/paged_and_layered_media#deliver_a_pdf_or_selected_pages_of_a_pdf
     */
    public function psdTools(...$pages)
    {
        return $this->addAction(ClassUtils::verifyVarArgsInstance($pages, BasePageAction::class));
    }
}
