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
 * Class ClippingPath
 */
class ClippingPath extends Action
{
    use PageIndexTrait;
    use PageNameTrait;

    /**
     * ClippingPath constructor.
     *
     * @param string        $clippingPath The clipping path name.
     * @param FlagParameter $method       The clipping method. Can be {@see Flag::clip()} or {@see Flag::clipEvenOdd()}.
     */
    public function __construct($clippingPath, FlagParameter $method)
    {
        parent::__construct($method, ClassUtils::verifyInstance($clippingPath, PageParam::class));
    }
}
