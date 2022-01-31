<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Logger;

use Cloudinary\Test\Unit\TestHelpers\TestLogger;
use Cloudinary\Test\Unit\UnitTestCase;
use Exception;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

/**
 * Class LoggerTest
 */
final class LoggerTest extends UnitTestCase
{
    /**
     * Checks that a different configurations create different handlers
     *
     * @dataProvider dataProvider
     *
     * @param array $config
     * @param int   $expectedCount
     * @param array $expectContainClasses
     */
    public function testHandlersConfiguration(
        array $config,
        $expectedCount,
        array $expectContainClasses
    ) {
        $testLogger = new TestLogger(['logging' => $config]);

        $testLogger->generateLog();
        self::assertCount($expectedCount, $testLogger->getHandlers());

        if (!empty($expectContainClasses)) {
            foreach ($expectContainClasses as $expectedClass) {
                self::assertContainsInstancesOf($expectedClass, $testLogger->getHandlers());
            }
        }
    }

    /**
     * Data provider with different configurations
     *
     * @return array
     */
    public static function dataProvider()
    {
        return [
            [
                'config' => [
                    'file' => [
                        'my_debug_file' => [
                            'path' => 'logs/cloudinary.log',
                        ],
                        'file_for_critical_logs' => [
                            'path' => 'logs/cloudinary_critical.log',
                            'level' => 'critical'
                        ],
                    ],
                    'error_log' => [
                        'level' => 'ERROR'
                    ]
                ],
                'expectedCount' => 3,
                'expectContainClasses' => [
                    ErrorLogHandler::class,
                    StreamHandler::class
                ],
            ],
            [
                'config' => [
                    'error_log' => [
                        'level' => 'ERROR'
                    ]
                ],
                'expectedCount' => 1,
                'expectContainClasses' => [
                    ErrorLogHandler::class,
                ],
            ],
            [
                'config' => [
                    'error_log' => true,
                ],
                'expectedCount' => 1,
                'expectContainClasses' => [
                    ErrorLogHandler::class,
                ],
            ],
            [
                'config' => [
                    'error_log' => false,
                ],
                'expectedCount' => 1,
                'expectContainClasses' => [
                    NullHandler::class,
                ],
            ],
            [
                'config' => [],
                'expectedCount' => 1,
                'expectContainClasses' => [
                    ErrorLogHandler::class,
                ],
            ],
            [
                'config' => ['error_log' => []],
                'expectedCount' => 1,
                'expectContainClasses' => [
                    ErrorLogHandler::class,
                ],
            ],
            [
                'config' => ['enabled' => false,],
                'expectedCount' => 0,
                'expectContainClasses' => [],
            ],

        ];
    }

    public function testTriggerErrorOnLoggerWrite()
    {
        $errorsCaught = [];
        $errorHandler = static function ($errNo, $errString) use (&$errorsCaught) {
            $errorsCaught[] = ['no' => $errNo, 'message' => $errString];
            return true;
        };

        set_error_handler($errorHandler);

        $logger = new TestLogger(
            [
                'logging' => [
                    'file' => [
                        'some-unreachable-path' => [
                            'path' => 'http://some-unreachable-website',
                            'level' => 'INFO',
                        ],
                    ],
                ],
            ]
        );

        /**
         * Generating logging activity twice to check that error triggered once
         */
        $exceptionsCaught = 0;

        try {
            $logger->generateLog();
        } catch (Exception $e) {
            $exceptionsCaught++;
        }

        // Checking that we got an error triggered
        self::assertCount(1, $errorsCaught);
        self::assertEquals(1, $exceptionsCaught);

        try {
            $logger->generateLog();
        } catch (Exception $e) {
            $exceptionsCaught++;
        }

        // Checking that exception's and error's count is still the same
        self::assertCount(1, $errorsCaught);
        self::assertEquals(1, $exceptionsCaught);

        restore_error_handler();
    }
}
