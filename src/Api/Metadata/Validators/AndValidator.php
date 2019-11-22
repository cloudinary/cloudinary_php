<?php

namespace Cloudinary\Metadata\Validators;

/**
 * Class AndValidator
 *
 * An 'And' rule validation used to combine other rules with an 'AND' logic relation between them.
 *
 * @package Cloudinary\Metadata\Validators
 */
class AndValidator extends MetadataValidation
{
    const RULE_AND = 'and';

    /**
     * @var array
     */
    protected $rules;

    /**
     * Create a new instance of the validator with the given rules.
     *
     * @param array $rules MetadataValidation. The rules to use.
     */
    public function __construct(array $rules)
    {
        $this->type = self::RULE_AND;
        $this->rules = $rules;
    }
}
