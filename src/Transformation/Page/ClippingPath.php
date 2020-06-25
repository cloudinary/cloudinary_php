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
 * Defines the clipping path to use when trimming pixels.
 *
 *
 * @api
 */
class ClippingPath extends Action
{
    use PageIndexTrait;
    use PageNameTrait;

    /**
     * ClippingPath constructor.
     *
     * @param string        $clippingPath The clipping path name.
     * @param FlagParameter $method       The clipping method. Can be Flag::clip() or Flag::clipEvenOdd().
     *
     * @see Flag::clip
     * @see Flag::clipEvenOdd
     */
    public function __construct($clippingPath, FlagParameter $method)
    {
        parent::__construct($method, ClassUtils::verifyInstance($clippingPath, PageParam::class));
    }
}
