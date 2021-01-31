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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class FormatQualifier
 */
class FormatQualifier extends BaseQualifier implements FormatInterface
{
    use FormatTrait;

    /**
     * Sets the file format.
     *
     * @param string $format The file format.
     *
     * @return static
     */
    public function format($format)
    {
        $this->setQualifierValue($format);

        return $this;
    }
}
