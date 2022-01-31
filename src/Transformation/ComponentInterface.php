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

use JsonSerializable;

/**
 * Interface ComponentInterface
 */
interface ComponentInterface extends JsonSerializable
{
    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize();
}
