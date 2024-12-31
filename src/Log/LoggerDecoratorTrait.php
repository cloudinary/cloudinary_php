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
 * Logger decorator that instantiates logger by configuration and exposes PSR-3 v3 Logger methods
 */
trait LoggerDecoratorTrait
{
    /**
     * Adds a log record at the DEBUG level.
     *
     * Used for logging fine-grained informational events that are most useful to debug an application.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    /**
     * Logs with an arbitrary level
     *
     * @param string             $level   The log level
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        if (! isset($this->logger)) {
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
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * Used for logging normal but significant events.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * Used for logging exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * Used for logging error events that might still allow the application to continue running.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * Used for logging critical events.
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * Used for logging and probably triggering an alert that will wake someone up.
     * Example: Entire website down, database unavailable, etc.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * Used for logging the highest level of errors. System is unusable.
     *
     * @param string|\Stringable $message The log message
     * @param array              $context The log context
     *
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }
}
