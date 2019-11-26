<?php

namespace Cloudinary\Metadata\Validators;

use Cloudinary\Metadata\Metadata;

/**
 * Class MetadataValidation
 *
 * Represents the base class for metadata fields validation mechanisms.
 *
 * @package Cloudinary\Metadata\Validators
 */
abstract class MetadataValidation extends Metadata
{
    protected $type;
    protected $min;
    protected $max;
    protected $equals;
    protected $value;
}
