<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Search;

use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\Test\Helpers\MockSearchFoldersApi;
use Cloudinary\Test\Helpers\MockSearchApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class SearchApiTest
 */
final class SearchApiTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    /**
     * Finds assets by multiple parameters.
     *
     * @throws GeneralError
     */
    public function testExecuteWithParams()
    {
        $mockSearchApi = new MockSearchApi();
        $mockSearchApi
            ->expression('format:png')
            ->maxResults(10)
            ->nextCursor(self::NEXT_CURSOR)
            ->sortBy('created_at', 'asc')
            ->sortBy('updated_at')
            ->aggregate('format')
            ->aggregate('resource_type')
            ->withField('tags')
            ->withField('image_metadata')
            ->execute();
        $lastRequest = $mockSearchApi->getMockHandler()->getLastRequest();

        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'sort_by'     => [
                    ['created_at' => 'asc'],
                    ['updated_at' => 'desc'],
                ],
                'aggregate'   => ['format', 'resource_type'],
                'with_field'  => ['tags', 'image_metadata'],
                'expression'  => 'format:png',
                'max_results' => 10,
                'next_cursor' => self::NEXT_CURSOR,
            ],
            'Should use right headers for execution of advanced search api'
        );
    }

    /**
     * Duplicates in `aggregate` and `with_field` fields should be deleted.
     * Duplicates in a `sort_by` field should be deleted, and if a duplicate exists, the value (asc/desc) should be
     * updated.
     *
     * @throws GeneralError
     */
    public function testShouldNotDuplicateValues()
    {
        $mockSearchApi = new MockSearchApi();
        $mockSearchApi
            ->sortBy('created_at', 'asc')
            ->sortBy('created_at')
            ->sortBy('public_id', 'asc')
            ->aggregate('format')
            ->aggregate('format')
            ->aggregate('resource_type')
            ->withField('context')
            ->withField('context')
            ->withField('tags')
            ->execute();
        $lastRequest = $mockSearchApi->getMockHandler()->getLastRequest();

        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'sort_by' => [
                    ['created_at' => 'desc'],
                    ['public_id' => 'asc'],
                ],
                'aggregate' => ['format', 'resource_type'],
                'with_field' => ['context', 'tags'],
            ]
        );
    }

    public function testShouldSearchFolders()
    {
        $mockSearchApi = new MockSearchFoldersApi();
        $mockSearchApi->expression('parent=folder_name')->execute();

        $lastRequest = $mockSearchApi->getMockHandler()->getLastRequest();

        self::assertStringEndsWith('folders/search', $lastRequest->getRequestTarget());
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'expression' => 'parent=folder_name',
            ]
        );
    }
}
