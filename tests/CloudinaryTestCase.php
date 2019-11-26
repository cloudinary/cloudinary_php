<?php

namespace Cloudinary\Test;

use PHPUnit\Framework\TestCase;

abstract class CloudinaryTestCase extends TestCase
{
    /**
     * Reports an error if the $haystack array does not contain the $needle array.
     * @param array $haystack
     * @param array $needle
     */
    protected static function assertArrayContainsArray($haystack, $needle)
    {
        $result = array_filter($haystack, function ($item) use ($needle) {
            return $item == $needle;
        });

        self::assertGreaterThanOrEqual(1, count($result), 'The $haystack array does not contain the $needle array');
    }
}
