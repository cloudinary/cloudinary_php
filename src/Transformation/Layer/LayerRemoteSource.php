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

use Cloudinary\StringUtils;

/**
 * Class LayerRemoteSource
 */
class LayerRemoteSource extends ParameterMultiValue
{
    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return StringUtils::base64UrlEncode(parent::__toString());
    }
}
