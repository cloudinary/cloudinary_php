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
     * @param array|string $json Configuration json
     *
     */
    public static function fromJson(array|string $json): static;

    /**
     * Creates a new instance using Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     */
    public static function fromCloudinaryUrl(string $cloudinaryUrl): static;

    /**
     * Imports configuration from a json string or an array as a source.
     *
     * @param array|string $json Configuration json
     *
     */
    public function importJson(array|string $json): static;

    /**
     * Imports configuration from a Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     */
    public function importCloudinaryUrl(string $cloudinaryUrl): static;

    /**
     * Serialises to a string representation.
     *
     */
    public function toString(): string;

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
     * @return array data which can be serialized by json_encode.
     */
    public function jsonSerialize(
        bool $includeSensitive = true,
        bool $includeEmptyKeys = false,
        bool $includeEmptySections = false
    ): array;
}
