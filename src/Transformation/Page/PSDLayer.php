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
 * Represents a layer in a Photoshop document.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/paged_and_layered_media#deliver_selected_layers_of_a_psd_image
 * target="_blank">Deliver selected layers of a PSD image</a>
 *
 * @api
 */
class PsdLayer extends BasePageAction
{
    use PageNameTrait;
    use PageNamesTrait;
    use PageIndexTrait;
    use PageRangeTrait;
}
