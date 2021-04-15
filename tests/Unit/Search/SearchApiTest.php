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
            ->nextCursor('8c452e112d4c88ac7c9ffb3a2a41c41bef24')
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
                'next_cursor' => '8c452e112d4c88ac7c9ffb3a2a41c41bef24',
            ],
            'Should use right headers for execution of advanced search api'
        );
    }
}
