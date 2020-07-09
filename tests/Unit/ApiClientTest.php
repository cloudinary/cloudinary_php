<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\Exception\ApiError;
use Exception;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger as Monolog;
use ReflectionException;
use ReflectionMethod;

/**
 * Class ApiClientTest
 */
final class ApiClientTest extends UnitTestCase
{
    /**
     * Test that attempting to upload a non-existent file logs an error and throws an exception
     *
     * @throws ReflectionException
     * @throws ApiError
     */
    public function testLoggingPostFileAsyncEmptyFilename()
    {
        $apiClient = new ApiClient();

        $message = null;
        $expectedLogMessage = 'Error while attempting to upload a file';
        $expectedExceptionMessage = 'fopen(): Filename cannot be empty';
        try {
            $apiClient->postFileAsync('/', '', []);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        self::assertStringStartsWith($expectedExceptionMessage, $message);
        self::assertObjectLoggedMessage($apiClient, $expectedLogMessage, Monolog::CRITICAL);
    }


    /**
     * @throws ReflectionException
     */
    public function testInvalidApiClientParseJsonResponse()
    {
        $apiClient = new ApiClient();
        $reflectionMethod = new ReflectionMethod(ApiClient::class, 'parseJsonResponse');
        $reflectionMethod->setAccessible(true);

        $message = null;
        $expectedExceptionMessage = 'Error parsing server response';
        try {
            $reflectionMethod->invoke($apiClient, new Response(200, [], '{NOT_A_JSON}'));
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        self::assertStringStartsWith($expectedExceptionMessage, $message);
        self::assertObjectLoggedMessage($apiClient, $expectedExceptionMessage, Monolog::ERROR);
    }
}
