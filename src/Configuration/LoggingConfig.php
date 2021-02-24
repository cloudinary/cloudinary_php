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
 * Defines the global configuration for logging messages when using the SDK.
 *
 * @property array  $file     Settings for logging messages to a file.
 * @property array  $errorLog Settings for logging messages to PHP error_log() handler.
 * @property array  $test     Settings for logging messages for testing purposes.
 * @property string $level    Settings for default logging level.
 * @property bool   $enabled  Settings for globally disabling all logging.
 *
 * @api
 */
class LoggingConfig extends BaseConfigSection
{
    const CONFIG_NAME = 'logging';

    // Supported parameters
    const FILE      = 'file';
    const ERROR_LOG = 'error_log';
    const TEST      = 'test';
    const LEVEL     = 'level';
    const ENABLED   = 'enabled';

    // Public properties available to users, should FULLY correspond constant values!
    public $file;
    public $errorLog;
    public $test;
    public $level;
    public $enabled;
}
