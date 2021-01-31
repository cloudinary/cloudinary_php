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
     * Asserts that a request contains the expected fields and values in multipart request headers.
     *
     * @param RequestInterface $request The request object
     * @param array|null       $fields  An array of keys and values to look for in the the request
     * @param string           $message
     */
    protected static function assertMultipartRequestHeadersSubset(
        RequestInterface $request,
        $fields = null,
        $message = ''
    ) {
        self::assertArraySubset(
            $fields,
            self::getMultipartRequestParts($request),
            $message ?: 'The expected fields and values were not found in the multipart\'s request headers'
        );
    }

    /**
     * Tests if a given request contains multipart form data.
     *
     * @param RequestInterface $request The request object
     *
     * @return bool
     */
    private static function requestContainsMultipartFormData(RequestInterface $request)
    {
        foreach ($request->getHeader('Content-Type') as $contentType) {
            if (preg_match('/^multipart\/form-data;\s*boundary=/i', $contentType)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a multipart request's individual parts.
     *
     * @param RequestInterface $request The request object
     *
     * @return array|null
     */
    private static function getMultipartRequestParts(RequestInterface $request)
    {
        $parts = [];

        if (!self::requestContainsMultipartFormData($request)) {
            return null;
        }
        $blocks = explode('--' . self::getRequestBoundary($request), $request->getBody()->getContents());

        foreach ($blocks as $block) {
            preg_match('/name=\"([^\"]*)\"/s', $block, $matches);
            if (!empty($matches) && !empty($matches[1])) {
                $valueMatches       = array_filter(preg_split("/[\r\n]+/", $block));
                $parts[$matches[1]] = end($valueMatches);
            }
        }

        return $parts;
    }

    /**
     * Returns the boundary used in a multipart/form-data request
     *
     * @param RequestInterface $request
     *
     * @return string|null
     */
    private static function getRequestBoundary(RequestInterface $request)
    {
        if (!self::requestContainsMultipartFormData($request)) {
            return null;
        }

        foreach ($request->getHeader('Content-Type') as $contentType) {
            preg_match('/boundary=(.*)$/', $contentType, $matches);
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }
    }
}
