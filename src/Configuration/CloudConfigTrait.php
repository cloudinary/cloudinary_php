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
 * Trait CloudConfigTrait
 *
 */
trait CloudConfigTrait
{
    /**
     * Sets the name of your Cloudinary cloud.
     *
     * @param string $cloudName The name of your Cloudinary cloud.
     *                          Used to build the public URL for all your media assets.
     *
     * @return $this
     *
     * @api
     */
    public function cloudName($cloudName)
    {
        return $this->setCloudConfig(CloudConfig::CLOUD_NAME, $cloudName);
    }

    /**
     * Sets the Cloud configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    abstract public function setCloudConfig($configKey, $configValue);
}
