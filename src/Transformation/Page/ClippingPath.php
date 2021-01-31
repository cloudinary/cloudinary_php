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
 * @api
 */
class ClippingPath extends BasePageAction
{
    use PageIndexTrait;
    use PageNameTrait;

    /**
     * ClippingPath constructor.
     *
     * @param string        $clippingPath The clipping path name.
     * @param FlagQualifier $method       The clipping method. Can be Flag::clip() or Flag::clipEvenOdd().
     *
     * @see Flag::clip
     * @see Flag::clipEvenOdd
     */
    public function __construct($clippingPath, FlagQualifier $method = null)
    {
        parent::__construct(ClassUtils::verifyInstance($clippingPath, PageQualifier::class));

        if (! $method) {
            $method = Flag::clip();
        }
        $this->setFlag($method);
    }

    /**
     * Trims pixels according to a clipping path included in the original image using an evenodd clipping rule.
     *
     * @return static
     */
    public function evenOdd()
    {
        return $this->unsetFlag(Flag::clip())->setFlag(Flag::clipEvenOdd());
    }
}
