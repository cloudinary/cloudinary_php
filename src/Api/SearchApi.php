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
use Cloudinary\ArrayUtils;
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
class SearchApi implements JsonSerializable
{
    /**
     * @internal
     */
    const SEARCH_API_ENDPOINT = 'resources/search';
    /**
     * @internal
     */
    const SORT_BY = 'sort_by';
    /**
     * @internal
     */
    const AGGREGATE = 'aggregate';
    /**
     * @internal
     */
    const WITH_FIELD = 'with_field';
    /**
     * @internal
     */
    const EXPRESSION = 'expression';
    /**
     * @internal
     */
    const MAX_RESULTS = 'max_results';
    /**
     * @internal
     */
    const NEXT_CURSOR = 'next_cursor';
    /**
     * @internal
     */
    const KEYS_WITH_UNIQUE_VALUES = [self::SORT_BY, self::AGGREGATE, self::WITH_FIELD];

    /**
     * @var array query object that includes the search query
     */
    private $query = [
        self::SORT_BY    => [],
        self::AGGREGATE  => [],
        self::WITH_FIELD => [],
    ];

    /**
     * @var ApiClient $apiClient The HTTP API client instance.
     */
    protected $apiClient;

    /**
     * SearchApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        $this->apiClient = new ApiClient($configuration);
    }

    /**
     * Sets the query string for filtering the assets in your cloud.
     *
     * If this parameter is not provided then all assets are listed (up to max_results).
     *
     * @param mixed $value The (Lucene-like) string expression specifying the search query.
     *
     * @return $this
     *
     * @api
     */
    public function expression($value)
    {
        $this->query[self::EXPRESSION] = $value;

        return $this;
    }

    /**
     * Sets the maximum number of results to return.
     *
     * @param int $value Default 50. Maximum 500.
     *
     * @return $this
     *
     * @api
     */
    public function maxResults($value)
    {
        $this->query[self::MAX_RESULTS] = $value;

        return $this;
    }

    /**
     * When a search request has more results to return than max_results, the next_cursor value is returned as
     * part of the response.
     *
     * You can then specify this value as the next_cursor parameter of the following request.
     *
     * @param string $value The next_cursor.
     *
     * @return $this
     *
     * @api
     */
    public function nextCursor($value)
    {
        $this->query[self::NEXT_CURSOR] = $value;

        return $this;
    }

    /**
     * Sets the `sort_by` field.
     *
     * @param string $fieldName The field to sort by. You can specify more than one sort_by parameter;
     *                          results will be sorted according to the order of the fields provided.
     * @param string $dir       Sort direction. Valid sort directions are 'asc' or 'desc'. Default: 'desc'.
     *
     * @return $this
     *
     * @api
     */
    public function sortBy($fieldName, $dir = 'desc')
    {
        $this->query[self::SORT_BY][$fieldName] = [$fieldName => $dir];

        return $this;
    }

    /**
     * The name of a field (attribute) for which an aggregation count should be calculated and returned in the response.
     *
     * (Tier 2 only).
     *
     * You can specify more than one aggregate parameter.
     *
     * @param string $value Supported values: resource_type, type, pixels (only the image assets in the response are
     *                      aggregated), duration (only the video assets in the response are aggregated), format, and
     *                      bytes. For aggregation fields without discrete values, the results are divided into
     *                      categories.
     *
     * @return $this
     *
     * @api
     */
    public function aggregate($value)
    {
        $this->query[self::AGGREGATE][$value] = $value;

        return $this;
    }

    /**
     * The name of an additional asset attribute to include for each asset in the response.
     *
     * @param string $value Possible value: context, tags, and for Tier 2 also image_metadata, and image_analysis.
     *
     * @return $this
     *
     * @api
     */
    public function withField($value)
    {
        $this->query[self::WITH_FIELD][$value] = $value;

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
        return $this->apiClient->postJsonAsync(self::SEARCH_API_ENDPOINT, $this);
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
     * Returns the query as an array.
     *
     * @return array
     *
     * @api
     */
    public function asArray()
    {
        return ArrayUtils::mapAssoc(
            static function ($key, $value) {
                return in_array($key, self::KEYS_WITH_UNIQUE_VALUES) ? array_values($value) : $value;
            },
            array_filter(
                $this->query,
                static function ($value) {
                    /** @noinspection TypeUnsafeComparisonInspection */
                    return ((is_array($value) && ! empty($value)) || ($value != null));
                }
            )
        );
    }

    /**
     * Serializes to JSON.
     *
     * @return mixed data which can be serialized by <b>json_encode</b>
     */
    public function jsonSerialize()
    {
        return $this->asArray();
    }
}
