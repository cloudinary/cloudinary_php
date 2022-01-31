<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration;

use JsonSerializable;

/**
 * Interface ConfigurableInterface
 */
interface ConfigurableInterface extends JsonSerializable
{
    /**
     * Creates a new instance using json string or an array as a source.
     *
     * @param string|array $json Configuration json
     *
     * @return static
     */
    public static function fromJson($json);

    /**
     * Creates a new instance using Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     * @return static
     */
    public static function fromCloudinaryUrl($cloudinaryUrl);

    /**
     * Imports configuration from a json string or an array as a source.
     *
     * @param string|array $json Configuration json
     *
     * @return static
     */
    public function importJson($json);

    /**
     * Imports configuration from a Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     * @return static
     */
    public function importCloudinaryUrl($cloudinaryUrl);

    /**
     * Serialises to a string representation.
     *
     * @return string
     */
    public function toString();

    /**
     * Serialises to a string representation.
     *
     * @return string
     */
    public function __toString();

    /**
     * Serialises to a json array.
     *
     * @param bool $includeSensitive     Whether to include sensitive keys during serialisation.
     * @param bool $includeEmptyKeys     Whether to include keys without values.
     * @param bool $includeEmptySections Whether to include sections without keys with non-empty values.
     *
     * @return mixed data which can be serialized by json_encode.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize($includeSensitive = true, $includeEmptyKeys = false, $includeEmptySections = false);
}
