<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use JsonSerializable;

/**
 * Interface AssetInterface
 */
interface AssetInterface extends JsonSerializable
{
    /**
     * Creates a new asset from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     */
    public static function fromString(string $string): mixed;

    /**
     * Creates a new asset from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public static function fromJson(array|string $json): mixed;

    /**
     * Creates a new asset from the provided source and an array of (legacy) parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     */
    public static function fromParams(string $source, array $params): mixed;

    /**
     * Imports data from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     */
    public function importString(string $string): mixed;

    /**
     * Imports data from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public function importJson(array|string $json): mixed;

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Serializes to json.
     *
     */
    public function jsonSerialize(): array;
}
