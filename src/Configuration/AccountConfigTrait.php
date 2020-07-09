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
 * Trait AccountConfigTrait
 *
 */
trait AccountConfigTrait
{
    /**
     * Sets the name of your Cloudinary account.
     *
     * @param string $cloudName The name of your Cloudinary account.
     *                          Used to build the public URL for all your media assets.
     *
     * @return $this
     *
     * @api
     */
    public function cloudName($cloudName)
    {
        return $this->setAccountConfig(AccountConfig::CLOUD_NAME, $cloudName);
    }

    /**
     * Sets the Account configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    abstract public function setAccountConfig($configKey, $configValue);
}
