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
use Cloudinary\Transformation\Expression\Expression;

/**
 * Trait TextFitTrait
 */
trait TextFitTrait
{
    /**
     * Sets the text fit.
     *
     * @param int|string|Expression|TextFit $widthOrTextFit The width in pixels or the TextFit class.
     * @param int|string|Expression         $height         The height in pixels.
     *
     * @return $this
     */
    public function textFit($widthOrTextFit, $height = null)
    {
        $this->addQualifier(ClassUtils::verifyInstance($widthOrTextFit, TextFit::class, null, $height));

        return $this;
    }
}
