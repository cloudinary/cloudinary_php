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

use UnexpectedValueException;

/**
 * Logger decorator that instantiates logger by configuration and exposes PSR-3 v1 Logger methods
 */
trait LoggerDecoratorV1Trait
{
    /**
     * Adds a log record at the DEBUG level.
     *
     * Used for logging fine-grained informational events that are most useful to debug an application.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log('debug', $message, $context);
    }

    /**
     * Logs with an arbitrary level
     *
     * @param string $level   The log level
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->logger === null) {
            $this->logger = LoggersList::instance()->getLogger($this->config);
        }

        if ($this->logger !== null) {
            static $handlerLogged;

            try {
                $this->logger->log($level, $message, $context);
            } catch (UnexpectedValueException $e) {
                if (! $handlerLogged) {
                    // Triggering the error once per execution
                    trigger_error($e, E_USER_WARNING);
                    $handlerLogged = true;
                    throw $e;
                }
            }
        }
    }

    /**
     * Adds a log record at the INFO level.
     *
     * Used for logging informational messages that highlight the progress of the application at coarse-grained level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log('info', $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * Used for logging normal but significant events.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log('notice', $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * Used for logging exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * Used for logging error events that might still allow the application to continue running.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log('error', $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * Used for logging critical events.
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log('critical', $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * Used for logging and probably triggering an alert that will wake someone up.
     * Example: Entire website down, database unavailable, etc.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log('alert', $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * Used for logging the highest level of errors. System is unusable.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log('emergency', $message, $context);
    }
}
