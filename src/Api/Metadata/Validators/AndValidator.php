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
 * An 'And' rule validation used to combine other rules with an 'AND' logic relation between them.
 *
 * @api
 */
class AndValidator extends MetadataValidation
{
    const RULE_AND = 'and';

    /**
     * @var MetadataValidation[]
     */
    protected $rules;

    /**
     * Create a new instance of the validator with the given rules.
     *
     * @param MetadataValidation[] $rules The rules to use.
     */
    public function __construct(array $rules)
    {
        $this->type  = self::RULE_AND;
        $this->rules = $rules;
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    public function getPropertyKeys()
    {
        return array_merge(parent::getPropertyKeys(), ['rules']);
    }
}
