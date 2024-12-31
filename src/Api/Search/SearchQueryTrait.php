<?php

namespace Cloudinary\Api\Search;

use Cloudinary\ArrayUtils;

/**
 * Trait SearchQueryTrait
 */
trait SearchQueryTrait
{
    /**
     * @var array query object that includes the search query
     */
    private array $query
        = [
            self::SORT_BY    => [],
            self::AGGREGATE  => [],
            self::WITH_FIELD => [],
            self::FIELDS     => [],
        ];

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
    public function expression(mixed $value): static
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
    public function maxResults(int $value): static
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
    public function nextCursor(string $value): static
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
    public function sortBy(string $fieldName, string $dir = 'desc'): static
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
    public function aggregate(string $value): static
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
    public function withField(string $value): static
    {
        $this->query[self::WITH_FIELD][$value] = $value;

        return $this;
    }

    /**
     * The list of the fields to include for each asset in the response.
     *
     * @param array|string $fields The fields' names.
     *
     * @return $this
     *
     * @api
     */
    public function fields(array|string $fields): static
    {
        foreach (ArrayUtils::build($fields) as $field) {
            $this->query[self::FIELDS][$field] = $field;
        }

        return $this;
    }

    /**
     * Sets the search query.
     *
     * @param array $query The search query.
     *
     * @return $this
     */
    public function query(array $query): static
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Returns the query as an array.
     *
     *
     * @api
     */
    public function asArray(): array
    {
        return ArrayUtils::mapAssoc(
            static fn($key, $value) => in_array($key, self::KEYS_WITH_UNIQUE_VALUES) ? array_values($value) : $value,
            array_filter(
                $this->query,
                static function ($value) {
                    /** @noinspection TypeUnsafeComparisonInspection */
                    return is_array($value) && ! empty($value) || $value != null;
                }
            )
        );
    }
}
