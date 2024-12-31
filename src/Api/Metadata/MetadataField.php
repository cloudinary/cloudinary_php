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

use Cloudinary\Api\Metadata\Validators\MetadataValidation;

/**
 * Represents a single metadata field. Use one of the derived classes in metadata API calls.
 *
 * @api
 */
abstract class MetadataField extends Metadata
{
    protected string $externalId;

    protected string $label;

    protected bool $mandatory;

    protected mixed $defaultValue;

    protected string $type;

    protected MetadataValidation $validation;

    protected array $restrictions;

    protected bool $defaultDisabled;

    /**
     * The MetadataField constructor.
     *
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    public function getPropertyKeys(): array
    {
        return [
            'externalId',
            'label',
            'mandatory',
            'defaultValue',
            'type',
            'validation',
            'restrictions',
            'defaultDisabled',
        ];
    }

    /**
     * Returns the type of this field.
     *
     * @return string The type name.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the default value of this field.
     *
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * Sets the default value of this field.
     *
     */
    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Gets the ID of this field.
     *
     * @return string The field ID.
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * Sets the ID of the string (auto-generated if this is left blank).
     *
     * @param string $externalId The ID to set.
     */
    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    /**
     * Gets the label of this field.
     *
     * @return string The label of the field.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Sets the label of this field.
     *
     * @param string $label The label to set.
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Gets a boolean indicating whether this fields is currently mandatory.
     *
     * @return bool A boolean indicating whether the field is mandatory.
     */
    public function getMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * Sets whether this field needs to be mandatory.
     *
     * @param bool $mandatory A boolean indicating whether the field should be mandatory.
     */
    public function setMandatory(bool $mandatory = true): void
    {
        $this->mandatory = $mandatory;
    }

    /**
     * Gets the validation rules of this field.
     *
     * @return MetadataValidation The validation rules.
     */
    public function getValidation(): MetadataValidation
    {
        return $this->validation;
    }

    /**
     * Sets the validation rules of this field.
     *
     * @param MetadataValidation $validation The rules to set.
     */
    public function setValidation(MetadataValidation $validation): void
    {
        $this->validation = $validation;
    }

    /**
     * Gets the restrictions of this field.
     *
     */
    public function getRestrictions(): array
    {
        return $this->restrictions;
    }

    /**
     * Sets the restrictions of this field.
     *
     */
    public function setRestrictions(array $restrictions): void
    {
        $this->restrictions = $restrictions;
    }

    /**
     * Gets the value indicating whether the field should be disabled by default.
     *
     */
    public function isDefaultDisabled(): bool
    {
        return $this->defaultDisabled;
    }

    /**
     * Sets the value indicating whether the field should be disabled by default.
     *
     * @param bool $defaultDisabled The value to set.
     */
    public function setDefaultDisabled(bool $defaultDisabled = true): void
    {
        $this->defaultDisabled = $defaultDisabled;
    }
}
