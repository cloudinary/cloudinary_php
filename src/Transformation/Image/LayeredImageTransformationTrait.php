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
     * Gets selected pages of a PDF.
     *
     * @param Page|PageParam|int ...$pages
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/paged_and_layered_media#deliver_a_pdf_or_selected_pages_of_a_pdf
     */
    public function getPage(...$pages)
    {
        return $this->addAction(ClassUtils::verifyVarArgsInstance($pages, PageParam::class));
    }

    /**
     * Gets selected frame of an animated image.
     *
     * @param $frameNumber
     *
     * @return static
     */
    public function getFrame($frameNumber)
    {
        return $this->getPage($frameNumber);
    }

    /**
     * Delivers an image containing only specified layers of a Photoshop image.
     *
     * @param PSDLayer|int ...$layers
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/paged_and_layered_media#deliver_selected_layers_of_a_psd_image
     */
    public function getLayer(...$layers)
    {
        return $this->getPage(...$layers);
    }

    /**
     * Extracts the original content of an embedded object of a Photoshop image.
     *
     * @param SmartObject|int ...$smartObjects
     *
     * @return static
     */
    public function getSmartObject(...$smartObjects)
    {
        return $this->getPage(SmartObject::createWithPageParam(...$smartObjects));
    }

    /**
     * Trims the pixels of a PSD image according to a Photoshop clipping path that is stored in the image's metadata.
     *
     * @param int|string $clippingPath Number or the name of the clipping path.
     *
     * @return static
     */
    public function clip($clippingPath)
    {
        return $this->addAction(ClassUtils::verifyInstance($clippingPath, ClippingPath::class, null, Flag::clip()));
    }

    /**
     * Trims pixels according to a clipping path included in the original image using an evenodd clipping rule.
     *
     * @param int|string $clippingPath Number or the name of the clipping path.
     *
     * @return static
     */
    public function clipEvenOdd($clippingPath)
    {
        return $this->addAction(
            ClassUtils::verifyInstance(
                $clippingPath,
                ClippingPath::class,
                null,
                Flag::clipEvenOdd()
            )
        );
    }
}
