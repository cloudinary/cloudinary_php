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

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Test\Integration\IntegrationTestCase;
use PHPUnit_Framework_Constraint_IsType as IsType;

/**
 * Class FoldersTest
 */
final class FoldersTest extends IntegrationTestCase
{
    private static $FOLDER_BASE_NAME;
    private static $FOLDER_NAME;
    private static $FOLDER2_NAME;
    private static $SUB_FOLDER_NAME;
    private static $SUB_FOLDER_CREATE_NAME;
    private static $SUB_FOLDER_DELETE_NAME;
    private static $SUB_FOLDER_FULL_PATH;
    private static $SUB_FOLDER_CREATE_FULL_PATH;
    private static $SUB_FOLDER_DELETE_FULL_PATH;
    private static $SUB_FOLDER_SINGLE_FULL_PATH;


    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$FOLDER_BASE_NAME = 'test_folder';
        self::$FOLDER_NAME = self::$FOLDER_BASE_NAME . '_' . self::$UNIQUE_TEST_ID;
        self::$FOLDER2_NAME = self::$FOLDER_BASE_NAME . '_2_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_NAME = 'test_sub_folder_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_CREATE_NAME = 'test_sub_folder_for_create_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_DELETE_NAME = 'test_sub_folder_for_delete_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_FULL_PATH = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_NAME;
        self::$SUB_FOLDER_CREATE_FULL_PATH = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_CREATE_NAME;
        self::$SUB_FOLDER_DELETE_FULL_PATH = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_DELETE_NAME;
        self::$SUB_FOLDER_SINGLE_FULL_PATH = self::$SUB_FOLDER_FULL_PATH . '/' . self::$SUB_FOLDER_NAME;

        self::uploadTestResourceImage(['folder' => self::$SUB_FOLDER_FULL_PATH]);
        self::createTestFolder(self::$SUB_FOLDER_DELETE_FULL_PATH);
        self::createTestFolder(self::$SUB_FOLDER_SINGLE_FULL_PATH);
        self::createTestFolder(self::$FOLDER2_NAME);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupFolder(self::$FOLDER_NAME);

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of all root folders
     */
    public function testListRootFolders()
    {
        $result = self::$adminApi->rootFolders();

        self::assertObjectStructure($result, ['total_count' => IsType::TYPE_INT]);
        $this->assertNotEmpty($result['folders']);
        self::assertValidFolder($result['folders'][0]);

        $result = self::$adminApi->rootFolders(['max_results' => 1]);

        $this->assertCount(1, $result['folders']);
        self::assertObjectStructure(
            $result,
            ['total_count' => IsType::TYPE_INT, 'next_cursor' => IsType::TYPE_STRING]
        );
        self::assertValidFolder($result['folders'][0]);
    }

    /**
     * Get sub folders for a given folder
     *
     * @throws ApiError
     */
    public function testListSubFolders()
    {
        $result = self::$adminApi->subfolders(self::$SUB_FOLDER_FULL_PATH);

        self::assertObjectStructure($result, ['total_count' => IsType::TYPE_INT]);
        $this->assertNotEmpty($result['folders']);
        self::assertValidFolder(
            $result['folders'][0],
            [
                'name' => self::$SUB_FOLDER_NAME,
                'path' => self::$SUB_FOLDER_SINGLE_FULL_PATH,
            ]
        );

        $result = self::$adminApi->subfolders(self::$FOLDER_NAME, ['max_results' => 1]);

        $this->assertCount(1, $result['folders']);
        self::assertObjectStructure(
            $result,
            ['total_count' => IsType::TYPE_INT, 'next_cursor' => IsType::TYPE_STRING]
        );
        self::assertValidFolder($result['folders'][0]);
    }

    /**
     * Create folder
     *
     * @throws ApiError
     */
    public function testCreateFolder()
    {
        self::createTestFolder(self::$SUB_FOLDER_CREATE_FULL_PATH);
    }

    /**
     * Delete folder
     *
     * @throws ApiError
     */
    public function testDeleteFolder()
    {
        $result = self::$adminApi->deleteFolder(self::$SUB_FOLDER_DELETE_FULL_PATH);

        $this->assertContains(self::$SUB_FOLDER_DELETE_FULL_PATH, $result['deleted']);
    }

    /**
     * create a folder and asserts that creation succeeded
     *
     * @param string $path
     *
     * @return ApiResponse
     * @throws ApiError
     */
    private static function createTestFolder($path)
    {
        $result = self::$adminApi->createFolder($path);
        $pathAsArray = explode('/', $path);
        $name = array_pop($pathAsArray);

        self::assertValidFolder($result, ['path' => $path, 'name' => $name]);
        self::assertTrue($result['success']);

        sleep(2);

        $folders = self::$adminApi->subfolders(implode('/', $pathAsArray));

        self::assertArrayContainsArray(
            $folders['folders'],
            [
                'name' => $name,
                'path' => $path
            ]
        );

        return $result;
    }
}
