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

use Cloudinary\Api\Metadata\Metadata;

/**
 * Represents the base class for metadata fields validation mechanisms.
 *
 * @api
 */
abstract class MetadataValidation extends Metadata
{
    protected string $type;

    protected int $min;

    protected int $max;

    protected bool $equals;

    protected mixed $value;

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    public function getPropertyKeys(): array
    {
        return ['type', 'min', 'max', 'equals', 'value'];
    }
}
