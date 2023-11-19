<?php

namespace Cloudinary\Api;

/**
 * Class HttpStatusCode
 *
 * HTTP request status codes.
 */
class HttpStatusCode
{
    /**
     * The 200 (OK) status code indicates that the request has succeeded.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.3.1
     *
     * @var int
     */
    const OK = 200;


    /**
     * The 400 (Bad Request) status code indicates that the server cannot or
     * will not process the request due to something that is perceived to be a
     * client error (e.g., malformed request syntax, invalid request message
     * framing, or deceptive request routing).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.1
     *
     * @var int
     */
    const BAD_REQUEST = 400;

    /**
     * The 401 (Unauthorized) status code indicates that the request has not
     * been applied because it lacks valid authentication credentials for the
     * target resource. The server generating a 401 response must send a
     * WWW-Authenticate header field (Section 4.1) containing at least one
     * challenge applicable to the target resource.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7235#section-3.1
     *
     * @var int
     */
    const UNAUTHORIZED = 401;

    /**
     * The 403 (Forbidden) status code indicates that the server understood the
     * request but refuses to authorize it. A server that wishes to make public
     * why the request has been forbidden can describe that reason in the
     * response payload (if any).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.3
     *
     * @var int
     */
    const FORBIDDEN = 403;

    /**
     * The 404 (Not Found) status code indicates that the origin server did not
     * find a current representation for the target resource or is not willing
     * to disclose that one exists.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.4
     *
     * @var int
     */
    const NOT_FOUND = 404;

    /**
     * The 409 (Conflict) status code indicates that the request could not be
     * completed due to a conflict with the current state of the target
     * resource. This code is used in situations where the user might be able to
     * resolve the conflict and resubmit the request. The server should generate
     * a payload that includes enough information for a user to recognize the
     * source of the conflict.¶
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.8
     *
     * @var int
     */
    const CONFLICT = 409;

    /**
     * Returned when you are being rate limited.
     *
     * @link https://dev.twitter.com/docs/rate-limiting/1
     *
     * @var int
     */
    const ENHANCE_YOUR_CALM = 420;

    /**
     * The 500 (Internal Server Error) status code indicates that the server
     * encountered an unexpected condition that prevented it from fulfilling the
     * request.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#section-6.6.1
     *
     * @var int
     */
    const INTERNAL_SERVER_ERROR = 500;

}
