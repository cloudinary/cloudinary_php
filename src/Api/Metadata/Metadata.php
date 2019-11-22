<?php

namespace Cloudinary\Metadata;

use Cloudinary\StringUtils;
use JsonSerializable;

/**
 * Class Metadata
 *
 * @package Cloudinary\Metadata
 */
abstract class Metadata implements JsonSerializable
{
    public function jsonSerialize()
    {
        $properties = get_object_vars($this);

        $snakeCaseProperties = [];
        foreach ($properties as $key => $value) {
            if ($value === null) {
                continue;
            } elseif (is_object($value)) {
                $snakeCaseProperties[StringUtils::camel_case_to_snake_case($key)] = $value->jsonSerialize();
            } elseif (is_array($value)) {
                $subArray = [];
                foreach ($value as $subArrayValue) {
                    $subArray[] = $subArrayValue->jsonSerialize();
                }
                $snakeCaseProperties[StringUtils::camel_case_to_snake_case($key)] = $subArray;
            } else {
                $snakeCaseProperties[StringUtils::camel_case_to_snake_case($key)] = $value;
            }
        }

        return $snakeCaseProperties;
    }
}