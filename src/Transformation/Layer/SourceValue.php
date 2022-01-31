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
 * Class SourceValue
 */
class SourceValue extends QualifierMultiValue
{
    const UNSAFE_DELIMITER = '/';
    const SAFE_DELIMITER   = ':';

    use LayerSourceTrait;

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return str_replace(self::UNSAFE_DELIMITER, self::SAFE_DELIMITER, parent::__toString());
    }
}
