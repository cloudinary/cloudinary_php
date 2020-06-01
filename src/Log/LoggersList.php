<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Log;

use Cloudinary\Configuration\LoggingConfig;

/**
 * Class LoggersList
 *
 */
class LoggersList
{
    /**
     * @var Logger[] $loggerInstances
     */
    private static $loggerInstances = [];

    /**
     * @var LoggersList $instance The instance of the LoggersList.
     */
    private static $instance = false;

    /**
     * LoggersList private constructor.
     * Use LoggersList::instance() to get the singleton instance of LoggersList
     */
    private function __construct()
    {
    }

    /**
     * Returns a singleton instance of the LoggersList.
     *
     * @return LoggersList
     */
    public static function instance()
    {
        if (self::$instance === false) {
            self::$instance = new LoggersList();
        }

        return self::$instance;
    }

    /**
     * @param LoggingConfig $config
     *
     * @return Logger|null
     */
    public static function getLogger(LoggingConfig $config)
    {
        if ($config->enabled === false) {
            return null;
        }

        $configHash = crc32(serialize($config));

        if (!isset(self::$loggerInstances[$configHash])) {
            self::$loggerInstances[$configHash] = new Logger($config);
        }

        return self::$loggerInstances[$configHash];
    }
}
