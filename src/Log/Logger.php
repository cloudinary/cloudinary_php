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

use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\LoggingConfig;
use Exception;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger as Monolog;

/**
 * Class Logger
 *
 * @api
 */
class Logger
{
    const LOGGER_NAME = 'cloudinary';

    /**
     * @var Monolog $entity
     */
    private $entity;

    /**
     * @var int $defaultLogLevel The default log level. Is set during initialization.
     */
    private $defaultLogLevel;

    /**
     * @var array Map of PHP error levels to PSR-3 log levels
     */
    private $errorLevelMap = [
        E_ERROR => Monolog::CRITICAL,
        E_WARNING => Monolog::WARNING,
        E_PARSE => Monolog::ALERT,
        E_NOTICE => Monolog::NOTICE,
        E_CORE_ERROR => Monolog::CRITICAL,
        E_CORE_WARNING => Monolog::WARNING,
        E_COMPILE_ERROR => Monolog::ALERT,
        E_COMPILE_WARNING => Monolog::WARNING,
        E_USER_ERROR => Monolog::ERROR,
        E_USER_WARNING => Monolog::WARNING,
        E_USER_NOTICE => Monolog::NOTICE,
        E_STRICT => Monolog::NOTICE,
        E_RECOVERABLE_ERROR => Monolog::ERROR,
        E_DEPRECATED => Monolog::NOTICE,
        E_USER_DEPRECATED => Monolog::NOTICE,
    ];

    /**
     * Logger constructor.
     *
     * @param LoggingConfig $config
     */
    public function __construct(LoggingConfig $config)
    {
        $this->entity = new Monolog(self::LOGGER_NAME);
        $this->init($config);
    }

    /**
     * @param LoggingConfig $config
     *
     * @return null|Logger
     */
    private function init(LoggingConfig $config)
    {
        if ($config->enabled === false) {
            return null;
        }

        try {
            $defaultLogLevel = $config->level ?: $this->getDefaultLogLevel();

            if (is_array($config->errorLog)) {
                $logLevel = ArrayUtils::get($config->errorLog, 'level', $defaultLogLevel);
                $this->addHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $logLevel));
            }

            if ($config->errorLog === true) {
                $this->addHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $defaultLogLevel));
            }

            if (is_array($config->file)) {
                foreach ($config->file as $loggingFileConfig) {
                    if (empty($loggingFileConfig['path']) || ! is_string($loggingFileConfig['path'])) {
                        continue;
                    }
                    $logLevel = ArrayUtils::get($loggingFileConfig, 'level', $defaultLogLevel);
                    $this->addHandler(new StreamHandler($loggingFileConfig['path'], $logLevel));
                }
            }

            if (is_array($config->test)) {
                $logLevel = ArrayUtils::get($config->test, 'level', $defaultLogLevel);
                $this->addHandler(new TestHandler($logLevel));
            }

            // If no handlers defined, fallback to default error log handler.
            if ($config->errorLog !== false && empty($this->getHandlers())) {
                $this->addHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $defaultLogLevel));
            }

            /**
             * If no handlers defined and errorLog disabled, use NullHandler
             * to avoid Monolog's default behavior that creates php://stderr StreamHandler on empty handlers list
             */
            if (empty($this->getHandlers())) {
                $this->addHandler(new NullHandler(Monolog::DEBUG));
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);

            return null;
        }

        return $this;
    }

    /**
     * @param HandlerInterface $handler
     *
     * @return Monolog
     */
    private function addHandler(HandlerInterface $handler)
    {
        foreach ($this->entity->getHandlers() as $entityHandler) {
            if ($entityHandler instanceof HandlerInterface
                && $entityHandler->getLevel() === $handler->getLevel()
            ) {
                return $this->entity;
            }
        }

        return $this->entity->pushHandler($handler);
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->entity->getHandlers();
    }

    /**
     * Gets TestHandler
     *
     * @return null|TestHandler
     */
    public function getTestHandler()
    {
        foreach ($this->entity->getHandlers() as $handler) {
            if ($handler instanceof TestHandler) {
                return $handler;
            }
        }

        return null;
    }

    /**
     * @param       $level
     * @param       $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->entity->log($level, $message, $context);
    }

    /**
     * Gets a PSR-3 log level based on the error level set in PHP
     *
     * Note: An error level of E_ALL will result in log messages of level NOTICE and higher being displayed.
     *
     * @return int PSR-3 log level
     */
    public function getDefaultLogLevel()
    {
        if ($this->defaultLogLevel !== null) {
            return $this->defaultLogLevel;
        }

        $this->defaultLogLevel = Monolog::EMERGENCY;

        $phpErrorReportingLevel = error_reporting();

        if ($phpErrorReportingLevel === E_ALL) {
            $this->defaultLogLevel = Monolog::NOTICE;

            return $this->defaultLogLevel;
        }

        // Convert PHP's error reporting level from an integer to a bitmask, go over each error level that is on
        // and translate that level to a matching PSR-3 log level, finally return the lowest matching PSR-3 log level.
        // For example, if E_ERROR and E_NOTICE are on, the function will return the PSR-3 NOTICE level.
        // Decimal to bitmask conversion code based on https://www.php.net/manual/en/errorfunc.constants.php#121911
        $pot = 0;
        foreach (array_reverse(str_split(decbin($phpErrorReportingLevel))) as $bit) {
            if ($bit === 1) {
                $this->defaultLogLevel = min($this->defaultLogLevel, $this->errorLevelMap[2 ** $pot]);
            }
            $pot++;
        }

        return $this->defaultLogLevel;
    }
}
