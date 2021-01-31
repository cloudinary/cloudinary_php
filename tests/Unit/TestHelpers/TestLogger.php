<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\TestHelpers;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Log\LoggerDecorator;

/**
 * Logger implementation for test purposes
 */
class TestLogger extends LoggerDecorator
{
    public function __construct($configuration = null)
    {
        if ($configuration === null) {
            $configuration = Configuration::instance();
        }

        if (is_array($configuration)) {
            $configuration = new Configuration($configuration);
        }

        parent::__construct($configuration->logging);
    }

    /**
     * Generates log entries
     */
    public function generateLog()
    {
        $this->info('This is an info level message');
        $this->debug('This is a debug level message');
    }
}
