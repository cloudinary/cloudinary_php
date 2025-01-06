<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Metadata;

use InvalidArgumentException;

/**
 * Controls structured metadata data entry.
 *
 * @api
 */
class MetadataDataEntry extends Metadata
{
    protected ?string $externalId;

    protected string $value;

    /**
     * MetadataDataEntry constructor.
     *
     */
    public function __construct(string $value, ?string $externalId = null)
    {
        $this->setValue($value);
        $this->setExternalId($externalId);
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     */
    public function getPropertyKeys(): array
    {
        return ['externalId', 'value'];
    }

    /**
     * Gets the value of the entry.
     *
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the value of the entry.
     *
     */
    public function setValue(string $value): void
    {
        if (is_null($value)) {
            throw new InvalidArgumentException('Metadata data entry value is not valid');
        }
        $this->value = $value;
    }

    /**
     * Gets the ID of the entry.
     *
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * Sets the ID of the entry. Will be auto-generated if left blank.
     *
     * @param ?string $externalId The external ID.
     */
    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }
}
