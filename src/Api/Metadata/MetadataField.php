<?php

namespace Cloudinary\Metadata;

use Cloudinary\Metadata\Validators\MetadataValidation;

/**
 * Class MetadataField
 *
 * @package Cloudinary\Metadata
 */
abstract class MetadataField extends Metadata
{
    protected $externalId;
    protected $label;
    protected $mandatory;
    protected $defaultValue;
    protected $type;
    protected $validation;

    /**
     * MetadataField constructor
     *
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * Return field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (string)$defaultValue;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param bool $mandatory
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    }

    /**
     * @return MetadataValidation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param MetadataValidation $validation
     */
    public function setValidation(MetadataValidation $validation)
    {
        $this->validation = $validation;
    }
}
