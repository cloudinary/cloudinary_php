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
 * Base class for all comparison (greater than/less than) validation rules.
 *
 * @api
 */
abstract class ComparisonRule extends MetadataValidation
{
    public const GREATER_THAN = 'greater_than';
    public const LESS_THAN    = 'less_than';

    /**
     * ComparisonRule constructor.
     *
     */
    public function __construct(string $type, mixed $value, bool $equals = false)
    {
        $this->type = $type;
        $this->setValue($value);
        $this->equals = $equals;
    }

    protected function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
