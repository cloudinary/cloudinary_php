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
use Psr\Log\LoggerInterface;

/**
 * LoggerTrait adds logging capabilities to a class
 *
 * By default it writes to the standard error log configured for PHP
 *
 * Example usage:
 *   class Foo
 *   {
 *       use LoggerTrait;
 *
 *       public function __construct($configuration = null)
 *       {
 *           if (is_null($configuration)) {
 *               $configuration = Configuration::instance(); // get global instance
 *           }
 *           $this->configuration($configuration);
 *       }
 *
 *       public function configuration($configuration)
 *       {
 *           $tempConfiguration = new Configuration($configuration);
 *           $this->logging = $tempConfiguration->logging;
 *           return $this;
 *       }
 *
 *       public function bar()
 *       {
 *           $this->getLogger()->info('This is an info level message');
 *           $this->getLogger()->debug('This is a debug level message');
 *       }
 *   }
 *
 * It can also be configured to log to different destinations.
 * For example, the following code will send all DEBUG level and higher messages to one log file, CRITICAL and higher
 * to another file, and ERROR level and higher to the default error log configured in PHP.
 *
 *   new Foo(
 *       [
 *           "cloud" => [...],
 *           "logging" => [
 *               "file" => [
 *                   "my_debug_file" => [
 *                       "path" => "logs/cloudinary.log",
 *                       "level" => "debug"
 *                   ],
 *                   "file_for_critical_logs" => [
 *                       "path" => "logs/cloudinary_critical.log",
 *                       "level" => "critical"
 *                   ],
 *               ],
 *               "error_log" => [
 *                   "level" => "ERROR"
 *               ]
 *           ]
 *       ]
 *   );
 *
 * Logging levels can be configured per destination, and any destination with no configured level will fallback to the
 * level defined in $config['logging']['level'] or to the level defined in PHP.
 *
 * For example, the following code will send all DEBUG level and higher messages to logs/cloudinary.log and CRITICAL
 * and higher messages to another file and the default error log configured in PHP.
 *
 *   new Foo(
 *       [
 *           "cloud" => [...],
 *           "logging" => [
 *               "level" => "CRITICAL"
 *               "file" => [
 *                   "my_debug_file" => [
 *                       "path" => "logs/cloudinary.log",
 *                       "level" => "debug"
 *                   ],
 *                   "file_for_critical_logs" => [
 *                       "path" => "logs/cloudinary_critical.log"
 *                   ],
 *               ],
 *               "error_log" => []
 *           ]
 *       ]
 *   );
 *
 * You can turn off all logging by passing `"enabled" => false` in the logging configuration.
 *
 *   new Foo(
 *       [
 *           "cloud" => [...],
 *           "logging" => [
 *               "enabled" => false
 *           ]
 *       ]
 *   );
 *
 * Logging can also be configured using the CLOUDINARY_URL ENV variable by passing additional params to URL.
 * For example, the following URL (long string split into multiple lines for readability):
 *
 * CLOUDINARY_URL=cloudinary://123456789012345:abcdeghijklmnopqrstuvwxyz12@n07t21i7
 * ?logging[level]=CRITICAL
 * &logging[file][my_debug_file][path]=logs/cloudinary.log&logging[file][my_debug_file][level]=debug
 * &logging[file][file_for_critical_logs][path]=logs/cloudinary_critical.log
 * &logging[error_log]=[]
 *
 * will be equal to this configuration
 *
 *   new Foo(
 *       [
 *           "cloud" => [...],
 *           "logging" => [
 *              "level" => "CRITICAL",
 *              "file" => [
 *                  "my_debug_file" => [
 *                      "path" => "logs/cloudinary.log",
 *                      "level" => "debug",
 *                  ],
 *                  "file_for_critical_logs" => [
 *                      "path" => "logs/cloudinary_critical.log",
 *                  ],
 *              ],
 *              "error_log" => [],
 *            ]
 *       ]
 *   );
 *
 *
 * If you do not pass any logging configuration, logging will revert to the default behavior defined in your
 * PHP configuration.
 */
trait LoggerTrait
{
    /**
     * @var LoggingConfig $logging
     */
    public $logging;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new LoggerDecorator($this->logging);
        }

        return $this->logger;
    }
}
