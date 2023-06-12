<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Search;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\Asset\SearchAsset;
use Cloudinary\Asset\SearchAssetTrait;
use GuzzleHttp\Promise\PromiseInterface;
use JsonSerializable;

/**
 * Class SearchApi
 *
 * The Cloudinary API search method allows you fine control on filtering and retrieving information on all the assets
 * in your cloud with the help of query expressions in a Lucene-like query language. A few examples of what you can
 * accomplish using the search method include:
 *
 *  * Searching by descriptive attributes such as public ID, filename, folders, tags, context, etc.
 *  * Searching by file details such as type, format, file size, dimensions, etc.
 *  * Searching by embedded data such as Exif, XMP, etc.
 *  * Searching by analyzed data such as the number of faces, predominant colors, auto-tags, etc.
 *  * Requesting aggregation counts on specified parameters, for example the number of assets found broken down by file
 * format.
 *
 * @api
 */
class SearchApi implements JsonSerializable, SearchQueryInterface
{
    use SearchQueryTrait;
    use SearchAssetTrait;

    /**
     * @internal
     */
    const ASSETS = 'resources';

    /**
     * @var string The Search API endpoint.
     */
    private $endpoint = self::ASSETS;

    /**
     * @var ApiClient $apiClient The HTTP API client instance.
     */
    protected $apiClient;

    /**
     * @var SearchAsset $searchAsset The Search Asset for building Search URL.
     */
    protected $searchAsset;

    /**
     * SearchApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        $this->apiClient   = new ApiClient($configuration);
        $this->searchAsset = new SearchAsset($configuration);
    }

    /**
     * Sets the Search API endpoint.
     *
     * @param string $endpoint The endpoint for the Search API.
     *
     * @return $this
     */
    public function endpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Executes the search API request asynchronously.
     *
     * @return PromiseInterface
     *
     * @api
     */
    public function executeAsync()
    {
        return $this->apiClient->postJsonAsync($this->getSearchEndpoint(), $this);
    }

    /**
     * Executes the search API request.
     *
     * @return ApiResponse
     *
     * @throws GeneralError
     *
     * @api
     */
    public function execute()
    {
        return $this->executeAsync()->wait();
    }

    /**
     * Creates a signed Search URL that can be used on the client side.
     *
     * @param int    $ttl        The time to live in seconds.
     * @param string $nextCursor Starting position.
     *
     * @return string The resulting search URL.
     */
    public function toUrl($ttl = null, $nextCursor = null)
    {
        $this->searchAsset->query($this->asArray());

        if ($ttl == null) {
            $ttl = $this->ttl;
        }

        return $this->searchAsset->toUrl($ttl, $nextCursor);
    }

    /**
     * Serializes to JSON.
     *
     * @return array data which can be serialized by <b>json_encode</b>
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->asArray();
    }

    /**
     * Returns the search endpoint.
     *
     * @return string
     */
    private function getSearchEndpoint()
    {
        return "{$this->endpoint}/search";
    }
}
