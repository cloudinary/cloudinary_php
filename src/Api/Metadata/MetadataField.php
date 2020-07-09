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
 * Class MetadataField
 *
 * Represents a single metadata field. Use one of the derived classes in the metadata API calls.
 *
 * @api
 */
abstract class MetadataField extends Metadata
{
    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    protected $mandatory;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var MetadataValidation
     */
    protected $validation;

    /**
     * MetadataField constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    public function getPropertyKeys()
    {
        return ['externalId', 'label', 'mandatory', 'defaultValue', 'type', 'validation'];
    }

    /**
     * Return the type of this field.
     *
     * @return string The name of the type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the default value of this field.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the default value of this field.
     *
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (string)$defaultValue;
    }

    /**
     * Gets the id of this field.
     *
     * @return string The field id.
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Sets the id of the string (auto-generated if this is left blank).
     *
     * @param string $externalId The id to set.
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * Gets the label of this field.
     *
     * @return string The label of the field.
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label of this field.
     *
     * @param string $label The label to set.
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Gets a boolean indicating whether this fields is mandatory.
     *
     * @return bool A boolean indicating whether the field is mandatory.
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Sets a boolean indicating whether this fields needs to be mandatory.
     *
     * @param bool $mandatory The boolean to set.
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    }

    /**
     * Gets the validation rules of this field.
     *
     * @return MetadataValidation The validation rules.
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set the validation rules of this field.
     *
     * @param MetadataValidation $validation The rules to set.
     */
    public function setValidation(MetadataValidation $validation)
    {
        $this->validation = $validation;
    }
}
