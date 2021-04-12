<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Cache;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Asset\Image;
use Cloudinary\Cache\Adapter\KeyValueCacheAdapter;
use Cloudinary\Cache\ResponsiveBreakpointsCache;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Test\Unit\Cache\Storage\DummyCacheStorage;
use PHPUnit_Framework_Constraint_IsType as IsType;

/**
 * Class ResponsiveBreakpointsCacheTest
 */
final class ResponsiveBreakpointsCacheTest extends IntegrationTestCase
{
    const R_B_CACHE_FETCH_BREAKPOINTS = 'r_b_cache_fetch_breakpoints';

    /**
     * @var ResponsiveBreakpointsCache
     */
    private static $cache;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::createTestAssets(
            [
                self::R_B_CACHE_FETCH_BREAKPOINTS => [
                    'options' => [
                        'file'                   => self::TEST_IMAGE_PATH,
                        'responsive_breakpoints' => [
                            [
                                'create_derived' => true,
                                'transformation' => [
                                    'crop'         => 'fill',
                                    'aspect_ratio' => '16:9',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        self::$cache = ResponsiveBreakpointsCache::instance();
        self::$cache->setCacheAdapter(new KeyValueCacheAdapter(new DummyCacheStorage()));
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * RBCache fetch breakpoints from cloudinary.
     */
    public function testRBCacheFetchBreakpointsFromCloudinary()
    {
        $actualBreakpoints = self::$cache->get(
            new Image(
                self::getTestAssetPublicId(self::R_B_CACHE_FETCH_BREAKPOINTS) . '.' . AssetTestCase::IMG_EXT
            ),
            true
        );

        self::assertInternalType(IsType::TYPE_ARRAY, $actualBreakpoints);
        self::assertGreaterThan(0, count($actualBreakpoints));
    }
}
