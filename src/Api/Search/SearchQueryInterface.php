<?php

namespace Cloudinary\Api\Search;

/**
 * Interface SearchQueryInterface.
 */
interface SearchQueryInterface
{
    /**
     * @internal
     */
    public const SORT_BY = 'sort_by';
    /**
     * @internal
     */
    public const AGGREGATE = 'aggregate';
    /**
     * @internal
     */
    public const WITH_FIELD = 'with_field';

    /**
     * @internal
     */
    public const FIELDS = 'fields';
    /**
     * @internal
     */
    public const EXPRESSION = 'expression';
    /**
     * @internal
     */
    public const MAX_RESULTS = 'max_results';
    /**
     * @internal
     */
    public const NEXT_CURSOR = 'next_cursor';
    /**
     * @internal
     */
    public const KEYS_WITH_UNIQUE_VALUES = [self::SORT_BY, self::AGGREGATE, self::WITH_FIELD, self::FIELDS];
}
