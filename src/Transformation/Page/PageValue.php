<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Class PageValue
 */
class PageValue extends QualifierMultiValue
{
    const VALUE_DELIMITER = ';';

    /**
     * Adds values.
     *
     * @param mixed $values The values to add.
     *
     * @return static
     *
     * @internal
     */
    public function addValues(...$values)
    {
        foreach ($values as $value) {
            $found = false;
            if ($value instanceof LayerName) {
                // here we aggregate all layer names under the same "name:" key.
                foreach ($this->arguments as $argument) {
                    if ($argument instanceof LayerName) {
                        $argument->addValues(...$value->getMultiValue());
                        $found = true;
                        break;
                    }
                }
            }
            if ($found) {
                continue;
            }

            $this->arguments[] = $value;
        }

        return $this;
    }
}
