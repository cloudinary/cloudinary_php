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

use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class Format
 */
class FormatParam extends BaseParameter implements FormatInterface
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
        $this->setParamValue($format);

        return $this;
    }
}
