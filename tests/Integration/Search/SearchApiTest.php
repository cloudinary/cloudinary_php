<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Search;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Search\SearchFoldersApi;
use Cloudinary\Api\Search\SearchApi;
use Cloudinary\Cloudinary;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\StringUtils;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Transformation;

/**
 * Class SearchApiTest
 */
class SearchApiTest extends IntegrationTestCase
{
    const CONTEXT_KEY    = 'key';
    const SEARCH_ASSET_1 = 'search_asset_1';
    const SEARCH_ASSET_2 = 'search_asset_2';
    const SEARCH_ASSET_3 = 'search_asset_3';

    const FOLDER_BASE_NAME = 'test_folder';

    private static $STRING_WITH_UNDERSCORE;
    private static $STRING_1;
    private static $STRING_2;
    private static $MULTI_STRING;

    private static $FOLDER_NAME;
    private static $FOLDER2_NAME;

    /**
     * @var SearchApi
     */
    public $search;

    /**
     * @var SearchFoldersApi
     */
    public $searchFolders;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Define a number of unique strings to be used in tags and public ids
        self::$STRING_WITH_UNDERSCORE = 'expression_' . self::$UNIQUE_TEST_ID;
        self::$STRING_1               = '1stString' . self::$SUFFIX;
        self::$STRING_2               = '2ndString' . self::$SUFFIX;
        self::$MULTI_STRING           = self::$STRING_1 . '_' . self::$STRING_2;

        self::$FOLDER_NAME  = self::FOLDER_BASE_NAME . '_' . self::$UNIQUE_TEST_ID;
        self::$FOLDER2_NAME = self::FOLDER_BASE_NAME . '_2_' . self::$UNIQUE_TEST_ID;

        $assets = [
            'options' => [
                'context' => ['stage' => 'value'],
                'eager'   => (new Transformation())->resize(Scale::scale(100)),
            ],
        ];

        self::createTestAssets(
            [
                self::SEARCH_ASSET_1 => $assets,
                self::SEARCH_ASSET_2 => $assets,
                self::SEARCH_ASSET_3 => $assets,
                [
                    'options' => [
                        'tags'      => [self::$STRING_WITH_UNDERSCORE, self::$STRING_1],
                        'public_id' => self::$STRING_1,
                        'folder'    => self::$FOLDER_NAME,
                    ],
                ],
                [
                    'options' => [
                        'tags'    => [self::$STRING_2],
                        'context' => [self::CONTEXT_KEY => self::$STRING_WITH_UNDERSCORE],
                        'folder'  => self::$FOLDER_NAME,
                    ],
                ],
                [
                    'options' => [
                        'tags'    => [
                            self::$STRING_WITH_UNDERSCORE,
                            self::$MULTI_STRING,
                        ],
                        'context' => [self::CONTEXT_KEY => self::$STRING_WITH_UNDERSCORE],
                        'folder'  => self::$FOLDER2_NAME,
                    ],
                ],
            ]
        );
        sleep(3); // FIXME
    }

    public function setUp()
    {
        parent::setUp();

        $this->search        = (new Cloudinary())->searchApi();
        $this->searchFolders = (new Cloudinary())->searchFoldersApi();
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();
    }

    /**
     * Assert that a search request with empty query parameters
     */
    public function testEmptyQuery()
    {
        $result = $this->search->asArray();

        self::assertCount(0, $result, 'Should generate an empty query JSON');
    }

    /**
     * Asserts that a filter value was added to a search query parameter
     */
    public function testShouldAddExpressionAsArray()
    {
        $query = $this->search->expression('format:jpg')->asArray();

        self::assertEquals(['expression' => 'format:jpg'], $query);
    }

    /**
     * Asserts that a sort value was added to a search query parameter
     */
    public function testShouldAddSortByAsArray()
    {
        $query = $this->search->sortBy('created_at', 'asc')->sortBy('updated_at', 'desc')->asArray();

        self::assertEquals(
            [
                'sort_by' => [
                    ['created_at' => 'asc'],
                    ['updated_at' => 'desc'],
                ],
            ],
            $query
        );
    }

    /**
     * Asserts that a limit value of results was added to a search query parameter
     */
    public function testShouldAddMaxResultsAsArray()
    {
        $query = $this->search->maxResults('10')->asArray();

        self::assertEquals(['max_results' => '10'], $query);
    }

    /**
     * Asserts that a `next_cursor` was added to a search query parameter
     */
    public function testShouldAddNextCursorAsArray()
    {
        $query = $this
            ->search
            ->nextCursor(
                'ec471a97ba510904ab57460b3ba3150ec29b6f8563eb1c10f6925ed0c6813f33cfa62ec6cf5ad96be6d6fa3ac3a76ccb'
            )
            ->asArray();

        self::assertEquals(
            [
                'next_cursor' =>
                    'ec471a97ba510904ab57460b3ba3150ec29b6f8563eb1c10f6925ed0c6813f33cfa62ec6cf5ad96be6d6fa3ac3a76ccb',
            ],
            $query
        );
    }

    /**
     * Asserts that aggregate fields was added to a search query parameter
     */
    public function testShouldAddAggregationsArgumentsAsArrayAsArray()
    {
        $query = $this->search->aggregate('format')->aggregate('size_category')->asArray();

        self::assertEquals(['aggregate' => ['format', 'size_category']], $query);
    }

    /**
     * Asserts that additional asset attribute was added to a search query parameter
     */
    public function testShouldAddWithFieldAsArray()
    {
        $query = $this->search->withField('context')->withField('tags')->asArray();
        self::assertEquals(['with_field' => ['context', 'tags']], $query);
    }

    /**
     * Finds assets by certain tag
     *
     * @throws ApiError
     */
    public function testShouldReturnAllImagesTagged()
    {
        $results = $this->search->expression('tags:' . self::$UNIQUE_TEST_TAG)->execute();

        self::assertCount(6, $results['resources']);
    }

    /**
     * Finds assets by public id
     *
     * @throws ApiError
     */
    public function testShouldReturnResource()
    {
        $results = $this->search->expression(
            'public_id:' . self::getTestAssetPublicId(self::SEARCH_ASSET_1)
        )->execute();

        self::assertCount(1, $results['resources']);
    }

    /**
     * Finds assets by asset id using a colon.
     *
     * @throws ApiError
     */
    public function testShouldReturnResourceByAssetIdUsingColon()
    {
        $results = $this->search->expression(
            'asset_id:' . self::getTestAssetAssetId(self::SEARCH_ASSET_1)
        )->execute();

        self::assertCount(1, $results['resources']);
    }

    /**
     * Finds assets by asset id using an equal sign.
     *
     * @throws ApiError
     */
    public function testShouldReturnResourceByAssetIdUsingEqualSign()
    {
        $results = $this->search->expression(
            'asset_id=' . self::getTestAssetAssetId(self::SEARCH_ASSET_1)
        )->execute();

        self::assertCount(1, $results['resources']);
    }

    /**
     * Find assets without limiting expression to certain fields
     * Shows results containing given text in any string field
     * This test will match two results where the expression is matched in tags and public_id
     *
     * @throws ApiError
     */
    public function testFindAssetsByGeneralExpression()
    {
        $result = $this->search
            ->expression(self::$STRING_1 . '*')
            ->maxResults(1)
            ->execute();

        self::assertEquals(2, $result['total_count']);
        self::assertCount(1, $result['resources']);
        self::assertValidAsset($result['resources'][0]);
    }

    /**
     * Find assets without limiting expression to certain fields but with an underscore in the expression
     * Shows results containing the entire expression in any string field
     * Shows results containing the entire expression or a part of it (parts are separated by underscore) in public_id
     *
     * @throws ApiError
     */
    public function testFindAssetsByGeneralExpressionWithUnderscore()
    {
        $result = $this->search
            ->expression(self::$MULTI_STRING)
            ->maxResults(2)
            ->execute();

        self::assertEquals(2, $result['total_count']);
        self::assertCount(2, $result['resources']);
        self::assertValidAsset($result['resources'][0]);
    }

    /**
     * Find assets with an expression limiting the search expression to certain fields
     * Shows results containing given text in tags field
     *
     * @throws ApiError
     */
    public function testFindAssetsByExpressionWithTag()
    {
        $result = $this->search
            ->expression('resource_type:image AND tags:' . self::$STRING_WITH_UNDERSCORE)
            ->withField('tags')
            ->maxResults(1)
            ->execute();

        self::assertEquals(2, $result['total_count']);
        self::assertCount(1, $result['resources']);
        self::assertValidAsset($result['resources'][0]);
        self::assertContains(self::$STRING_WITH_UNDERSCORE, $result['resources'][0]['tags']);
    }

    /**
     * Find assets by expression and returns sorted results
     * Shows results in a certain order containing given text in tags field
     *
     * @throws ApiError
     */
    public function testFindAssetsByExpressionSorted()
    {
        $result = $this->search
            ->expression('resource_type:image AND tags:' . self::$STRING_WITH_UNDERSCORE)
            ->withField('tags')
            ->sortBy('public_id', 'desc')
            ->maxResults(2)
            ->execute();

        self::assertCount(2, $result['resources']);

        // Verify that $result['resources'] is sorted by public_id in a descending order. We do this by sorting the
        // result ourselves and comparing the sorted results to the result returned from the Search API.
        $resources = $result['resources'];
        usort(
            $resources,
            static function ($a, $b) {
                if ($a['public_id'] === $b['public_id']) {
                    return 0;
                }

                return ($a['public_id'] > $b['public_id']) ? -1 : 1;
            }
        );

        self::assertEquals($resources, $result['resources']);
    }

    /**
     * Find assets with a negative expression limiting results returned
     * Shows results containing given text in context but not as a tag in the tags field
     * Return aggregate field
     *
     * @throws ApiError
     */
    public function testFindAssetsByExpressionWithoutCertainTag()
    {
        $expression = 'resource_type:image'
                      . ' AND context.key:' . self::$STRING_WITH_UNDERSCORE
                      . ' AND -tags:' . self::$STRING_WITH_UNDERSCORE;

        try {
            $result = $this->search
                ->expression($expression)
                ->withField('tags')
                ->maxResults(1)
                ->aggregate('format')
                ->execute();
        } catch (BadRequest $br) {
            if (StringUtils::contains($br->getMessage(), 'Your subscription plan does not support aggregations')) {
                $this->markTestSkipped($br->getMessage());
            }
        }

        self::assertEquals(1, $result['aggregations']['format']['gif']);
        self::assertEquals(1, $result['total_count']);
        self::assertCount(1, $result['resources']);
        self::assertValidAsset($result['resources'][0]);
    }

    public function testSearchFoldersApi()
    {
        $result = $this->searchFolders
            ->expression(self::FOLDER_BASE_NAME . '*')
            ->maxResults(2)
            ->execute();

        self::assertGreaterThan(1, $result['total_count']);
        self::assertCount(2, $result['folders']);
        self::assertStringContainsString(self::FOLDER_BASE_NAME, $result['folders'][0]['name']);
    }
}
