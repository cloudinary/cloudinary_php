<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\Test\Unit\Asset\AssetTestCase;
use DOMDocument;

/**
 * Class TagTestCase
 */
abstract class TagTestCase extends AssetTestCase
{
    /**
     * @param $expectedValue
     * @param $actualTag
     * @param $attributeName
     */
    protected static function assertTagAttributeEquals(
        $expectedValue,
        $actualTag,
        $attributeName
    ) {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($actualTag);
        $actualElement = $doc->getElementsByTagName($actualTag::NAME)->item(0);
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        self::assertEquals(
            (string)$expectedValue,
            $actualElement->getAttribute($attributeName),
            "Should contain attribute '$attributeName'"
        );
    }

    /**
     * @param $expectedSrcValue
     * @param $actualTag
     */
    protected static function assertTagSrcEquals($expectedSrcValue, $actualTag)
    {
        self::assertTagAttributeEquals($expectedSrcValue, $actualTag, 'src');
    }
}
