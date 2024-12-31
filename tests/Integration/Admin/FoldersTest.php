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
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Test\Integration\IntegrationTestCase;
use PHPUnit\Framework\Constraint\IsType;

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

        self::$FOLDER_BASE_NAME            = 'test_folder';
        self::$FOLDER_NAME                 = self::$FOLDER_BASE_NAME . '_' . self::$UNIQUE_TEST_ID;
        self::$FOLDER2_NAME                = self::$FOLDER_BASE_NAME . '_2_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_NAME             = 'test_sub_folder_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_CREATE_NAME      = 'test_sub_folder_for_create_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_DELETE_NAME      = 'test_sub_folder_for_delete_' . self::$UNIQUE_TEST_ID;
        self::$SUB_FOLDER_FULL_PATH        = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_NAME;
        self::$SUB_FOLDER_CREATE_FULL_PATH = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_CREATE_NAME;
        self::$SUB_FOLDER_DELETE_FULL_PATH = self::$FOLDER_NAME . '/' . self::$SUB_FOLDER_DELETE_NAME;
        self::$SUB_FOLDER_SINGLE_FULL_PATH = self::$SUB_FOLDER_FULL_PATH . '/' . self::$SUB_FOLDER_NAME;

        self::createTestAssets(
            ['options' => ['folder' => self::$SUB_FOLDER_FULL_PATH]]
        );
        self::createTestFolder(self::$SUB_FOLDER_DELETE_FULL_PATH);
        self::createTestFolder(self::$SUB_FOLDER_SINGLE_FULL_PATH);
        self::createTestFolder(self::$FOLDER2_NAME);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();
        self::cleanupFolder(self::$FOLDER_NAME);
        self::cleanupFolder(self::$FOLDER2_NAME);

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of all root folders.
     */
    public function testListRootFolders()
    {
        $result = self::$adminApi->rootFolders();

        self::assertObjectStructure($result, ['total_count' => IsType::TYPE_INT]);
        self::assertNotEmpty($result['folders']);
        self::assertValidFolder($result['folders'][0]);

        $result = self::$adminApi->rootFolders(['max_results' => 1]);

        self::assertCount(1, $result['folders']);
        self::assertObjectStructure(
            $result,
            ['total_count' => IsType::TYPE_INT, 'next_cursor' => IsType::TYPE_STRING]
        );
        self::assertValidFolder($result['folders'][0]);
    }

    /**
     * Get sub folders for a given folder.
     *
     * @throws ApiError
     */
    public function testListSubFolders()
    {
        $result = self::$adminApi->subfolders(self::$SUB_FOLDER_FULL_PATH);

        self::assertObjectStructure($result, ['total_count' => IsType::TYPE_INT]);
        self::assertNotEmpty($result['folders']);
        self::assertValidFolder(
            $result['folders'][0],
            [
                'name' => self::$SUB_FOLDER_NAME,
                'path' => self::$SUB_FOLDER_SINGLE_FULL_PATH,
            ]
        );

        $result = self::$adminApi->subfolders(self::$FOLDER_NAME, ['max_results' => 1]);

        self::assertCount(1, $result['folders']);
        self::assertObjectStructure(
            $result,
            ['total_count' => IsType::TYPE_INT, 'next_cursor' => IsType::TYPE_STRING]
        );
        self::assertValidFolder($result['folders'][0]);
    }

    /**
     * Create folder.
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
     */
    public function testDeleteFolder()
    {
        $result = self::$adminApi->deleteFolder(self::$SUB_FOLDER_DELETE_FULL_PATH);

        self::assertContains(self::$SUB_FOLDER_DELETE_FULL_PATH, $result['deleted']);
    }

    /**
     * Create a folder and asserts that creation succeeded.
     *
     * @param string $path
     *
     * @return ApiResponse
     * @throws ApiError
     * @throws \Exception
     */
    private static function createTestFolder($path)
    {
        $result      = self::$adminApi->createFolder($path);
        $pathAsArray = explode('/', $path);
        $name        = array_pop($pathAsArray);

        self::assertValidFolder($result, ['path' => $path, 'name' => $name]);
        self::assertTrue($result['success']);

        self::retryAssertionIfThrows(
            static function () use ($path, $name, $pathAsArray) {
                $folders = self::$adminApi->subfolders(implode('/', $pathAsArray));

                self::assertArrayContainsArray(
                    $folders['folders'],
                    [
                        'name' => $name,
                        'path' => $path,
                    ]
                );
            }
        );

        return $result;
    }

    /**
     * Should throw exception on non-existing folder.
     *
     */
    public function testFolderListingError()
    {
        $this->expectException(NotFound::class);

        self::$adminApi->subfolders('non-existent-subfolder');
    }
}
