<?php

namespace Cloudinary\Metadata\Validators;

/**
 * Class StringLength
 *
 * A validator to validate string lengths
 *
 * @package Cloudinary\Metadata\Validators
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
