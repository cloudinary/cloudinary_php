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

use Cloudinary\StringUtils;
use JsonSerializable;

/**
 * The root structured metadata class.
 *
 * @api
 */
abstract class Metadata implements JsonSerializable
{
    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    abstract protected function getPropertyKeys();

    /**
     * Returns data that should be serialized to JSON.
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $propertyKeys = $this->getPropertyKeys();

        $snakeCaseProperties = [];
        foreach ($propertyKeys as $key) {
            $value = $this->{$key};
            if ($value === null) {
                continue;
            }
            if (is_object($value)) {
                if (method_exists($value, 'jsonSerialize')) {
                    $snakeCaseProperties[StringUtils::camelCaseToSnakeCase($key)] = $value->jsonSerialize();
                }
            } elseif (is_array($value)) {
                $subArray = [];
                foreach ($value as $subArrayValue) {
                    if (method_exists($subArrayValue, 'jsonSerialize')) {
                        $subArray[] = $subArrayValue->jsonSerialize();
                    }
                }
                $snakeCaseProperties[StringUtils::camelCaseToSnakeCase($key)] = $subArray;
            } else {
                $snakeCaseProperties[StringUtils::camelCaseToSnakeCase($key)] = $value;
            }
        }

        return $snakeCaseProperties;
    }
}
