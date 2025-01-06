<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api;

/**
 * Class HttpMethod
 *
 * HTTP request methods
 */
abstract class HttpMethod
{
    /**
     * @var string The GET method requests a representation of the specified resource.
     *             Requests using GET should only retrieve data.
     */
    public const GET = 'GET';
    /**
     * @var string The HEAD method asks for a response identical to that of a GET request,
     *             but without the response body.
     */
    public const HEAD = 'HEAD';
    /**
     * @var string  The POST method is used to submit an entity to the specified resource, often causing a change in
     *              state or side effects on the server.
     */
    public const POST = 'POST';
    /**
     * @var string The PUT method replaces all current representations of the target resource with the request payload.
     */
    public const PUT = 'PUT';
    /**
     * @var string The DELETE method deletes the specified resource.
     */
    public const DELETE = 'DELETE';
    /**
     * @var string The CONNECT method establishes a tunnel to the server identified by the target resource.
     */
    public const CONNECT = 'CONNECT';
    /**
     * @var string The OPTIONS method is used to describe the communication options for the target resource.
     */
    public const OPTIONS = 'OPTIONS';
    /**
     * @var string The TRACE method performs a message loop-back test along the path to the target resource.
     */
    public const TRACE = 'TRACE';
    /**
     * @var string The PATCH method is used to apply partial modifications to a resource.
     */
    public const PATCH = 'PATCH';
}
