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
    protected const ASSETS = 'resources';

    /**
     * @var string The Search API endpoint.
     */
    private string $endpoint = self::ASSETS;

    /**
     * @var ApiClient $apiClient The HTTP API client instance.
     */
    protected ApiClient $apiClient;

    /**
     * @var SearchAsset $searchAsset The Search Asset for building Search URL.
     */
    protected SearchAsset $searchAsset;

    /**
     * SearchApi constructor.
     *
     * @param mixed|null $configuration
     */
    public function __construct(mixed $configuration = null)
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
    public function endpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Executes the search API request asynchronously.
     *
     *
     * @api
     */
    public function executeAsync(): PromiseInterface
    {
        return $this->apiClient->postJsonAsync($this->getSearchEndpoint(), $this);
    }

    /**
     * Executes the search API request.
     *
     *
     *
     * @api
     */
    public function execute(): ApiResponse
    {
        return $this->executeAsync()->wait();
    }

    /**
     * Creates a signed Search URL that can be used on the client side.
     *
     * @param int|null    $ttl        The time to live in seconds.
     * @param string|null $nextCursor Starting position.
     *
     * @return string The resulting search URL.
     */
    public function toUrl(?int $ttl = null, ?string $nextCursor = null): string
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
    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    /**
     * Returns the search endpoint.
     */
    private function getSearchEndpoint(): string
    {
        return "{$this->endpoint}/search";
    }
}
