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
    const FIELDS = 'fields';
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
    const KEYS_WITH_UNIQUE_VALUES = [self::SORT_BY, self::AGGREGATE, self::WITH_FIELD, self::FIELDS];
}
