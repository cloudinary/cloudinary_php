<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class UploadMappingsTest
 */
final class UploadMappingsTest extends IntegrationTestCase
{
    const TEMPLATE = 'https://cloudinary.com/';
    const TEMPLATE_2 = 'https://cloudinary.com/documentation';

    private static $FOLDER_NAME;
    private static $FOLDER_NAME_CREATE;
    private static $FOLDER_NAME_UPDATE;
    private static $FOLDER_NAME_DELETE;

    private static $FOLDERS = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$FOLDERS[] = self::$FOLDER_NAME = 'upload_mapping_folder_name_' . self::$UNIQUE_TEST_ID;
        self::$FOLDERS[] = self::$FOLDER_NAME_CREATE = 'upload_mapping_folder_name_create_' . self::$UNIQUE_TEST_ID;
        self::$FOLDERS[] = self::$FOLDER_NAME_UPDATE = 'upload_mapping_folder_name_update_' . self::$UNIQUE_TEST_ID;
        self::$FOLDERS[] = self::$FOLDER_NAME_DELETE = 'upload_mapping_folder_name_delete_' . self::$UNIQUE_TEST_ID;

        self::$adminApi->createUploadMapping(self::$FOLDER_NAME, ['template' => self::TEMPLATE]);
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$FOLDERS as $folderName) {
            self::cleanupUploadMapping($folderName);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Get all upload mappings
     */
    public function testGetUploadMappings()
    {
        $result = self::$adminApi->uploadMappings();

        self::assertNotEmpty($result['mappings']);
        self::assertValidUploadMapping($result['mappings'][0]);
        self::assertArrayContainsArray(
            $result['mappings'],
            [
                'folder' => self::$FOLDER_NAME,
                'template' => self::TEMPLATE
            ]
        );
    }

    /**
     * Get the details of a single upload mapping
     */
    public function testGetDetailUploadMapping()
    {
        $uploadMapping = self::$adminApi->uploadMapping(self::$FOLDER_NAME);

        self::assertValidUploadMapping(
            $uploadMapping,
            [
                'template' => self::TEMPLATE,
                'folder' => self::$FOLDER_NAME
            ]
        );
    }

    /**
     * Create an upload mapping
     */
    public function testCreateUploadMapping()
    {
        $result = self::$adminApi->createUploadMapping(
            self::$FOLDER_NAME_CREATE,
            ['template' => self::TEMPLATE]
        );

        self::assertNotEmpty($result);
        self::assertEquals('created', $result['message']);

        $uploadMapping = self::$adminApi->uploadMapping(self::$FOLDER_NAME_CREATE);

        self::assertValidUploadMapping(
            $uploadMapping,
            [
                'template' => self::TEMPLATE,
                'folder' => self::$FOLDER_NAME_CREATE
            ]
        );
    }

    /**
     * Update an upload mapping
     *
     * @throws ApiError
     */
    public function testUpdateUploadMapping()
    {
        self::$adminApi->createUploadMapping(self::$FOLDER_NAME_UPDATE, ['template' => self::TEMPLATE]);

        $result = self::$adminApi->updateUploadMapping(
            self::$FOLDER_NAME_UPDATE,
            ['template' => self::TEMPLATE_2]
        );

        self::assertEquals('updated', $result['message']);

        $uploadMapping = self::$adminApi->uploadMapping(self::$FOLDER_NAME_UPDATE);

        self::assertValidUploadMapping(
            $uploadMapping,
            [
                'template' => self::TEMPLATE_2,
                'folder' => self::$FOLDER_NAME_UPDATE
            ]
        );
    }

    /**
     * Delete an upload mapping
     *
     * @throws ApiError
     */
    public function testDeleteUploadMapping()
    {
        self::$adminApi->createUploadMapping(self::$FOLDER_NAME_DELETE, ['template' => self::TEMPLATE]);

        $result = self::$adminApi->deleteUploadMapping(self::$FOLDER_NAME_DELETE);

        self::assertEquals('deleted', $result['message']);

        $this->expectException(NotFound::class);
        self::$adminApi->uploadMapping(self::$FOLDER_NAME_DELETE);
    }
}
