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

/**
 * Trait TagConfigTrait
 *
 * @api
 */
trait TagConfigTrait
{

    /**
     * Image format of the video poster.
     *
     * @param string $format Image format.
     *
     * @return $this
     */
    public function videoPosterFormat($format): static
    {
        return $this->setTagConfig(TagConfig::VIDEO_POSTER_FORMAT, $format);
    }


    /**
     * Use fetch format transformation ("f_") instead of file extension.
     *
     * @param bool $useFetchFormat
     *
     * @return $this
     */
    public function useFetchFormat($useFetchFormat = true): static
    {
        return $this->setTagConfig(TagConfig::USE_FETCH_FORMAT, $useFetchFormat);
    }

    /**
     * Sets the Tag configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    abstract public function setTagConfig($configKey, $configValue): static;
}
