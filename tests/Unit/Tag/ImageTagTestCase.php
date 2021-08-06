<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\Image;

/**
 * Class ImageTagTest
 */
abstract class ImageTagTestCase extends TagTestCase
{
    const MIN_WIDTH            = 375;
    const MAX_WIDTH            = 3840;
    const BREAKPOINTS_ARR      = [828, 1366, 1536, 1920, 3840];
    const BREAKPOINTS_ARR_HALF = [414, 683, 768, 960, 1920];
    const MAX_IMAGES           = 5; // length of the self::BREAKPOINTS_ARR=
    const SIZES_ATTR           = '100vw';

    /**
     * @var Image $src
     */
    protected $src;

    public function setUp()
    {
        parent::setUp();

        $this->src = new Image(self::IMAGE_NAME);
    }

    /**
     * @param $expectedTagParams
     * @param $actualTag
     *
     */
    protected static function assertImageTag($expectedTagParams, $actualTag)
    {
        /**
         * @var Image $asset
         */
        $asset = $expectedTagParams[0];

        self::assertTagSrcEquals($asset, $actualTag);

        $breakpoints = ArrayUtils::get($expectedTagParams, 1);
        $attributes  = ArrayUtils::get($expectedTagParams, 2, []);

        $expectedImageTag = self::expectedImageTag(
            $asset->getPublicId(),
            self::getAssetHostNameAndType($asset),
            $asset->getTransformation(),
            null,
            $breakpoints,
            $attributes
        );

        self::assertEquals($expectedImageTag, (string)$actualTag);
    }

    /**
     * @param $asset
     *
     * @return string|null
     */
    protected static function getAssetHostNameAndType($asset)
    {
        $hostname = self::invokeNonPublicMethod($asset, 'finalizeDistribution');
        $type     = self::invokeNonPublicMethod($asset, 'finalizeAssetType');

        return "$hostname/$type/";
    }

    /**
     * @param $expectedTagParams
     * @param $actualTag
     *
     */
    protected static function assertPictureSourceTag($expectedTagParams, $actualTag)
    {
        /**
         * @var Image $asset
         */
        $asset = $expectedTagParams[0];

        $breakpoints = ArrayUtils::get($expectedTagParams, 1);
        $attributes  = ArrayUtils::get($expectedTagParams, 2, []);

        $expectedSourceTag = self::expectedSourceTag(
            $asset->getPublicId(),
            self::getAssetHostNameAndType($asset),
            $asset->getTransformation(),
            null,
            $breakpoints,
            $attributes
        );

        self::assertEquals($expectedSourceTag, (string)$actualTag);
    }

    /**
     * Helper method for generating expected `img` and `source` tags
     *
     * @param string $tag_name              Expected tag name(img or source)
     * @param        $distribution
     * @param string $public_id             Public ID of the image
     * @param string $common_trans_str      Default transformation string to be used in all resources
     * @param string $custom_trans_str      Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcset_breakpoints    Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @param bool   $is_void               Indicates whether tag is an HTML5 void tag (does not need to be self-closed)
     *
     * @return string Resulting tag
     *
     * @internal
     */
    private static function commonImageTagHelper(
        $tag_name,
        $distribution,
        $public_id,
        $common_trans_str,
        $custom_trans_str = '',
        $srcset_breakpoints = [],
        $attributes = [],
        $is_void = false
    ) {
        if (empty($custom_trans_str)) {
            $custom_trans_str = $common_trans_str;
        }

        if (! empty($srcset_breakpoints)) {
            $single_srcset_image  = static function ($w) use ($distribution, $public_id, $custom_trans_str) {
                return $distribution .
                       ArrayUtils::implodeUrl([$custom_trans_str, "c_scale,w_{$w}", $public_id]) .
                       " {$w}w";
            };
            $attributes['srcset'] = implode(', ', array_map($single_srcset_image, $srcset_breakpoints));
        }

        $tag = "<$tag_name";

        $attributes_str = implode(
            ' ',
            array_map(
                static function ($k, $v) {
                    if (is_bool($v)) {
                        return $v ? $k : '';
                    }

                    return "$k=\"$v\"";
                },
                array_keys($attributes),
                array_values($attributes)
            )
        );

        if (! empty($attributes_str)) {
            $tag .= " {$attributes_str}";
        }

        $tag .= '>'; //HTML5 void elements do not need to be self closed

        if (getenv('DEBUG')) {
            echo preg_replace('/([,\']) /', "$1\n    ", $tag) . "\n\n";
        }

        return $tag;
    }

    /**
     * Helper method for test_image_tag_srcset for generating expected image tag
     *
     * @param string $public_id             Public ID of the image
     * @param        $distribution
     * @param string $common_trans_str      Default transformation string to be used in all resources
     * @param string $custom_trans_str      Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcset_breakpoints    Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @return string Resulting image tag
     * @internal
     */
    protected static function expectedImageTag(
        $public_id,
        $distribution,
        $common_trans_str,
        $custom_trans_str = '',
        $srcset_breakpoints = [],
        $attributes = []
    ) {
        ArrayUtils::prependAssoc(
            $attributes,
            'src',
            $distribution . ArrayUtils::implodeUrl([$common_trans_str, $public_id])
        );

        return self::commonImageTagHelper(
            'img',
            $distribution,
            $public_id,
            $common_trans_str,
            $custom_trans_str,
            $srcset_breakpoints,
            $attributes
        );
    }

    /**
     * @param string $public_id             Public ID of the image
     * @param        $distribution
     * @param string $common_trans_str      Default transformation string to be used in all resources
     * @param string $custom_trans_str      Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcset_breakpoints    Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @return string Resulting `source` tag
     * @internal
     * Helper method for for generating expected `source` tag
     *
     */
    protected static function expectedSourceTag(
        $public_id,
        $distribution,
        $common_trans_str,
        $custom_trans_str = '',
        $srcset_breakpoints = [],
        $attributes = []
    ) {
        ArrayUtils::prependAssoc(
            $attributes,
            'srcset',
            $distribution . ArrayUtils::implodeUrl([$common_trans_str, $public_id])
        );

        //ksort($attributes); // Used here to produce output similar to Cloudinary::html_attrs

        return self::commonImageTagHelper(
            'source',
            $distribution,
            $public_id,
            $common_trans_str,
            $custom_trans_str,
            $srcset_breakpoints,
            $attributes
        );
    }
}
