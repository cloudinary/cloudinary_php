<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api;

use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\CloudConfig;
use InvalidArgumentException;

/**
 * Class UploadApiClient
 *
 * @internal
 */
class UploadApiClient extends ApiClient
{
    /**
     * Validates if all required authorization params are passed.
     *
     * @param CloudConfig $cloudConfig A config to validate.
     * @param array       $options     An array containing request body and additional options passed to the HTTP
     *                                 Client.
     *
     * @throws InvalidArgumentException In a case not all required keys are set.
     *
     * @internal
     */
    protected static function validateAuthorization($cloudConfig, $options)
    {
        if (!ArrayUtils::get($options, 'unsigned')) {
            parent::validateAuthorization($cloudConfig, $options);
        }
    }
}
