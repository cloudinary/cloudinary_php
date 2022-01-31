<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin\Assets;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Asset\AssetType;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class RestoreAssetsTest
 */
final class RestoreAssetsTest extends IntegrationTestCase
{
    const RESTORE_ASSET  = 'restore_asset';
    const BACKUP_ASSET_1 = 'backup_asset_1';
    const BACKUP_ASSET_2 = 'backup_asset_2';

    private static $UNIQUE_TEST_TAG_RESTORE;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_TEST_TAG_RESTORE = 'asset_restore_' . self::$UNIQUE_TEST_TAG;

        self::createTestAssets(
            [
                self::RESTORE_ASSET => [
                    'options' => [
                        'tags'   => [self::$UNIQUE_TEST_TAG_RESTORE],
                        'backup' => true,
                    ],
                ],
                self::BACKUP_ASSET_1 => [
                    'options' => [
                        'backup' => true,
                    ],
                ],
                self::BACKUP_ASSET_2 => [
                    'options' => [
                        'backup' => true,
                        'transformation' => ['angle' => 0],
                    ],
                ],
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Restore deleted assets by public_id.
     *
     * @throws ApiError
     */
    public function testRestoreDeletedAssetsByPublicId()
    {
        $result = self::$adminApi->deleteAssets(
            [
                self::getTestAssetPublicId(self::RESTORE_ASSET),
            ]
        );

        self::assertAssetDeleted(
            $result,
            self::getTestAssetPublicId(self::RESTORE_ASSET)
        );

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        self::assertEmpty($result['resources']);

        $result = self::$adminApi->restore(
            self::getTestAssetPublicId(self::RESTORE_ASSET)
        );

        self::assertEquals(
            AssetType::IMAGE,
            $result[self::getTestAssetPublicId(self::RESTORE_ASSET)][AssetType::KEY]
        );

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        self::assertCount(1, $result['resources']);
    }

    /**
     * Restore two different deleted assets.
     *
     * @throws ApiError
     */
    public function testRestoreDifferentDeletedAssets()
    {
        $deleteResult = self::$adminApi->deleteAssets(
            [
                self::getTestAssetPublicId(self::BACKUP_ASSET_1),
                self::getTestAssetPublicId(self::BACKUP_ASSET_2),
            ]
        );

        self::assertAssetDeleted($deleteResult, self::getTestAssetPublicId(self::BACKUP_ASSET_1), 2);
        self::assertAssetDeleted($deleteResult, self::getTestAssetPublicId(self::BACKUP_ASSET_2), 2);

        $secondAsset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::BACKUP_ASSET_1),
            ['versions' => true]
        );
        $thirdAsset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::BACKUP_ASSET_2),
            ['versions' => true]
        );

        $restoreResult = self::$adminApi->restore(
            [
                self::getTestAssetPublicId(self::BACKUP_ASSET_1),
                self::getTestAssetPublicId(self::BACKUP_ASSET_2),
            ],
            [
                'versions' => [
                    $secondAsset['versions'][0]['version_id'],
                    $thirdAsset['versions'][0]['version_id'],
                ],
            ]
        );

        self::assertEquals(
            $restoreResult[self::getTestAssetPublicId(self::BACKUP_ASSET_1)]['bytes'],
            self::getTestAsset(self::BACKUP_ASSET_1)['bytes']
        );
        self::assertEquals(
            $restoreResult[self::getTestAssetPublicId(self::BACKUP_ASSET_2)]['bytes'],
            self::getTestAsset(self::BACKUP_ASSET_2)['bytes']
        );
    }

    /**
     * Gets asset backups.
     */
    public function testBackupAsset()
    {
        $asset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::RESTORE_ASSET),
            [
                'versions' => true
            ]
        );

        self::assertGreaterThanOrEqual(1, $asset['versions']);
    }
}
