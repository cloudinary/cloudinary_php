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
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\TestHandler;
use Psr\Log\LoggerInterface;

/**
 * Logger decorator that instantiates logger by configuration and exposes PSR-3 Logger methods
 */
class LoggerDecorator implements LoggerInterface
{
    use LoggerDecoratorTrait;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var LoggingConfig
     */
    private $config;

    /**
     * LoggerDecorator constructor.
     *
     * @param LoggingConfig|null $config
     */
    public function __construct(?LoggingConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * Get the TestHandler (if one has been defined)
     *
     * @return TestHandler|null
     */
    public function getTestHandler()
    {
        if ($this->logger !== null) {
            return $this->logger->getTestHandler();
        }

        return null;
    }

    /**
     * Get all Monolog handlers used by this logger
     *
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        if ($this->logger !== null) {
            return $this->logger->getHandlers();
        }

        return [];
    }
}
