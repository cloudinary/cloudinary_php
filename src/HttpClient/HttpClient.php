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

use Cloudinary\Configuration\Configuration;
use Cloudinary\Exception\Error;
use Cloudinary\Log\LoggerTrait;
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
    use LoggerTrait;

    /**
     * @var Client $httpClient The HTTP client instance.
     */
    protected Client $httpClient;

    /**
     * HttpClient constructor.
     *
     * @param Configuration|null $configuration Configuration source.
     */
    public function __construct(?Configuration $configuration = null)
    {
        $this->httpClient = new Client();

        if ($configuration === null) {
            $configuration = Configuration::instance();
        }

        $this->logging = $configuration->logging;
    }

    /**
     * Retrieves JSON from url.
     *
     * @param string $url The url
     *
     *
     * @throws Error
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getJson(string $url): mixed
    {
        try {
            return self::parseJsonResponse($this->httpClient->get($url));
        } catch (Error $e) {
            $this->getLogger()->critical(
                'Error parsing JSON server response',
                [
                    'exception' => $e->getMessage(),
                    'class' => self::class,
                    'url' => $url,
                ]
            );
            throw $e;
        }
    }

    /**
     * Parses JSON response.
     *
     * @param ResponseInterface $response Response from HTTP request to Cloudinary server
     *
     *
     * @throws Error
     */
    private static function parseJsonResponse(ResponseInterface $response): mixed
    {
        try {
            $responseJson = JsonUtils::decode($response->getBody());
        } catch (InvalidArgumentException $iae) {
            $message = sprintf(
                'Error parsing server response (%s) - %s. Got - %s',
                $response->getStatusCode(),
                $response->getBody(),
                $iae
            );
            throw new Error($message);
        }

        return $responseJson;
    }
}
