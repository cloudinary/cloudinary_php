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
 * Class UploadPresetsTest
 */
final class UploadPresetsTest extends IntegrationTestCase
{
    const ALLOWED_FORMATS = 'jpg,png';

    private static $UPLOAD_PRESET_WITH_NAME;
    private static $UPLOAD_PRESET_WITH_PARAMETERS;
    private static $UPLOAD_PRESET_UPDATE;
    private static $UPLOAD_PRESET_UPDATE_TAG;

    private static $UPLOAD_PRESETS = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $id = self::$UNIQUE_TEST_ID;
        self::$UPLOAD_PRESETS[] = self::$UPLOAD_PRESET_WITH_NAME = 'upload_preset_with_name_' . $id;
        self::$UPLOAD_PRESETS[] = self::$UPLOAD_PRESET_WITH_PARAMETERS = 'upload_preset_with_parameters_' . $id;
        self::$UPLOAD_PRESETS[] = self::$UPLOAD_PRESET_UPDATE = 'upload_preset_update_' . $id;
        self::$UPLOAD_PRESETS[] = self::$UNIQUE_UPLOAD_PRESET;
        self::$UPLOAD_PRESET_UPDATE_TAG = self::$UPLOAD_PRESET_UPDATE . '_tag';

        self::createUploadPreset(
            [
                'name' => self::$UNIQUE_UPLOAD_PRESET,
                'tags' => self::$ASSET_TAGS,
                'allowed_formats' => self::ALLOWED_FORMATS
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$UPLOAD_PRESETS as $preset) {
            self::cleanupUploadPreset($preset);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Get all upload presets
     */
    public function testGetUploadPresets()
    {
        $result = self::$adminApi->uploadPresets();

        self::assertGreaterThanOrEqual(1, count($result['presets']));
        self::assertValidUploadPreset($result['presets'][0]);
    }

    /**
     * Get a single upload preset
     */
    public function testGetUploadPreset()
    {
        $uploadPreset = self::$adminApi->uploadPreset(self::$UNIQUE_UPLOAD_PRESET);

        self::assertValidUploadPreset(
            $uploadPreset,
            [
                'name' => self::$UNIQUE_UPLOAD_PRESET,
                'unsigned' => false
            ],
            [
                'tags' => self::$ASSET_TAGS,
                'allowed_formats' => explode(',', self::ALLOWED_FORMATS)
            ]
        );
    }

    /**
     * Create Upload Preset without name
     */
    public function testCreateUploadPresetWithNoName()
    {
        $result = self::createUploadPreset();

        self::$UPLOAD_PRESETS[] = $result['name'];

        $uploadPreset = self::$adminApi->uploadPreset($result['name']);

        self::assertValidUploadPreset(
            $uploadPreset,
            [
                'name' => $result['name'],
                'unsigned' => false
            ]
        );
    }

    /**
     * Create Upload Preset With name
     */
    public function testCreateUploadPresetWithName()
    {
        self::createUploadPreset(['name' => self::$UPLOAD_PRESET_WITH_NAME]);

        $uploadPreset = self::$adminApi->uploadPreset(self::$UPLOAD_PRESET_WITH_NAME);

        self::assertValidUploadPreset(
            $uploadPreset,
            [
                'name' => self::$UPLOAD_PRESET_WITH_NAME,
                'unsigned' => false
            ]
        );
    }

    /**
     * Create Upload Preset With Parameters
     */
    public function testCreateUploadPresetWithParameters()
    {
        self::createUploadPreset(
            [
                'name' => self::$UPLOAD_PRESET_WITH_PARAMETERS,
                'unsigned' => true,
                'tags' => self::$ASSET_TAGS,
                'allowed_formats' => 'jpg',
                'live' => true,
                'eval' => self::TEST_EVAL_STR
            ]
        );

        $uploadPreset = self::$adminApi->uploadPreset(self::$UPLOAD_PRESET_WITH_PARAMETERS);

        self::assertValidUploadPreset(
            $uploadPreset,
            [
                'name' => self::$UPLOAD_PRESET_WITH_PARAMETERS,
                'unsigned' => true,
            ],
            [
                'tags' => self::$ASSET_TAGS,
                'allowed_formats' => ['jpg'],
                'live' => true,
                'eval' => self::TEST_EVAL_STR
            ]
        );
    }

    /**
     * Update upload presets
     *
     * @throws ApiError
     */
    public function testUpdateUploadPresetDetails()
    {
        self::createUploadPreset(['name' => self::$UPLOAD_PRESET_UPDATE]);

        $result = self::$adminApi->updateUploadPreset(
            self::$UPLOAD_PRESET_UPDATE,
            [
                'unsigned' => true,
                'tags' => self::$UPLOAD_PRESET_UPDATE_TAG,
                'live' => true,
                'eval' => self::TEST_EVAL_STR
            ]
        );

        self::assertEquals('updated', $result['message']);

        $uploadPreset = self::$adminApi->uploadPreset(self::$UPLOAD_PRESET_UPDATE);

        self::assertValidUploadPreset(
            $uploadPreset,
            [
                'name' => self::$UPLOAD_PRESET_UPDATE,
                'unsigned' => true
            ],
            [
                'tags' => [self::$UPLOAD_PRESET_UPDATE_TAG],
                'live' => true,
                'eval' => self::TEST_EVAL_STR
            ]
        );
    }

    /**
     * Delete Upload Preset
     *
     * @throws ApiError
     */
    public function testDeleteUploadPreset()
    {
        $uploadPreset = self::createUploadPreset();

        self::$UPLOAD_PRESETS[] = $uploadPreset['name'];

        $result = self::$adminApi->deleteUploadPreset($uploadPreset['name']);

        self::assertEquals('deleted', $result['message']);

        $this->expectException(NotFound::class);
        self::$adminApi->uploadPreset($uploadPreset['name']);
    }
}
