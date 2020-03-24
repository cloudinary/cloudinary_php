<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary;

use Cloudinary\Exception\Error;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClient
 *
 * @internal
 */
class HttpClient
{
    /**
     * @var Client $httpClient The HTTP client instance.
     */
    protected $httpClient;

    /**
     * HttpClient constructor.
     *
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Retrieves JSON from url.
     *
     * @param string $url The url
     *
     * @return mixed
     *
     * @throws Error
     */
    public function getJson($url)
    {
        return self::parseJsonResponse($this->httpClient->get($url));
    }

    /**
     * Parses JSON response.
     *
     * @param ResponseInterface $response Response from HTTP request to Cloudinary server
     *
     * @return mixed
     *
     * @throws Error
     */
    private static function parseJsonResponse($response)
    {
        try {
            $responseJson = JsonUtils::decode($response->getBody(), true);
        } catch (InvalidArgumentException $iae) {
            throw new Error(
                "Error parsing server response ({$response->getStatusCode()}) - {$response->getBody()}. Got - {$iae}"
            );
        }

        return $responseJson;
    }
}
