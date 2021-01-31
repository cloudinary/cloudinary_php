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
 * Represents an embedded smart object in a Photoshop document.
 *
 * **Learn more**:
 * <a
 * href=https://cloudinary.com/documentation/paged_and_layered_media#extract_the_original_content_of_an_embedded_object
 * target="_blank">Extract the original content of an embedded Photoshop object</a>
 *
 * @api
 */
class SmartObject extends BasePageAction
{
    const MAIN_QUALIFIER = SmartObjectQualifier::class;

    use PageLayerNameTrait;
    use PageLayerNamesTrait;
    use PageIndexTrait;
}
