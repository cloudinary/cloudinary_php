<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Metadata\Validators;

/**
 * A validator to validate string lengths.
 *
 * @api
 */
class StringLength extends MetadataValidation
{
    const STRLEN = 'strlen';

    /**
     * Create a new instance with the given min and max.
     *
     * @param int $min Minimum valid string length.
     * @param int $max Maximum valid string length.
     */
    public function __construct($min, $max)
    {
        $this->type = self::STRLEN;
        $this->min = $min;
        $this->max = $max;
    }
}
