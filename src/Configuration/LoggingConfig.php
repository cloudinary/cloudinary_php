<?php

namespace Cloudinary\Configuration;

/**
 * Class LoggingConfig
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
