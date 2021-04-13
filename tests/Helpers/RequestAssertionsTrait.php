<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Helpers;

use Cloudinary\Api\ApiClient;
use Cloudinary\Configuration\Configuration;
use Psr\Http\Message\RequestInterface;

use function GuzzleHttp\Psr7\parse_query;

/**
 * Trait RequestAssertionsTrait
 *
 * @package Cloudinary\Test\Traits
 */
trait RequestAssertionsTrait
{
    /**
     * Assert that a request was made to the correct url.
     *
     * @param RequestInterface $request
     * @param string           $path
     * @param string           $message
     */
    protected static function assertRequestUrl(RequestInterface $request, $path, $message = '')
    {
        $config = Configuration::instance();

        self::assertEquals(
            '/' . ApiClient::apiVersion() . '/' . $config->cloud->cloudName . $path,
            $request->getUri()->getPath(),
            $message
        );
    }

    /**
     * Asserts that a request's query string contains the expected fields and values.
     *
     * @param RequestInterface $request The request object
     * @param array|null       $fields  An array of keys and values to look for in the the request
     * @param string           $message
     */
    protected static function assertRequestQueryStringSubset(RequestInterface $request, $fields = null, $message = '')
    {
        self::assertArraySubset(
            $fields,
            parse_query($request->getUri()->getQuery()),
            $message ?: 'The expected fields and values were not found in the request query string'
        );
    }

    /**
     * Asserts that a request contains the expected fields and values in its body.
     *
     * @param RequestInterface $request The request object
     * @param array|null       $fields  An array of keys and values to look for in the the request
     * @param string           $message
     */
    protected static function assertRequestBodySubset(RequestInterface $request, $fields = null, $message = '')
    {
        self::assertArraySubset(
            $fields,
            parse_query($request->getBody()->getContents()),
            $message ?: 'The expected fields and values were not found in the request body'
        );
    }

    /**
     * Asserts that a request contains the expected fields and values in json format.
     *
     * @param RequestInterface $request
     * @param array|null       $fields
     * @param string           $message
     */
    protected static function assertRequestJsonBodySubset(RequestInterface $request, $fields = null, $message = '')
    {
        self::assertArraySubset(
            $fields,
            json_decode($request->getBody()->getContents(), true),
            false,
            $message ?: 'The expected fields and values were not found in the request body'
        );
    }
}
