<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Media;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ConfigUtils;
use Cloudinary\StringUtils;
use Cloudinary\Test\CloudinaryTestCase;
use Cloudinary\Test\Helpers\Addon;
use Cloudinary\Test\Helpers\Feature;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use PHPUnit\Framework\Constraint\IsType;
use ReflectionClass;
use RuntimeException;
use Teapot\StatusCode\Http as HttpStatusCode;

/**
 * Class IntegrationTestCase
 */
abstract class IntegrationTestCase extends CloudinaryTestCase
{
    const TEST_ASSETS_DIR     = __DIR__ . '/../assets/';
    const TEST_IMAGE_PATH     = self::TEST_ASSETS_DIR . AssetTestCase::IMAGE_NAME;
    const TEST_IMAGE_GIF_PATH = self::TEST_ASSETS_DIR . AssetTestCase::IMAGE_NAME_GIF;
    const TEST_DOCX_PATH      = self::TEST_ASSETS_DIR . AssetTestCase::DOCX_NAME;
    const TEST_VIDEO_PATH     = self::TEST_ASSETS_DIR . AssetTestCase::VIDEO_NAME;
    const TEST_LOGGING        = ['logging' => ['test' => ['level' => 'debug']]];
    const TEST_EVAL_STR
                              = 'if (resource_info["width"] < 450) { upload_options["quality_analysis"] = true }; ' .
                                'upload_options["context"] = "width=" + resource_info["width"]';

    private static $TEST_ASSETS = [];

    protected static $UNIQUE_UPLOAD_PRESET;

    /**
     * @var AdminApi
     */
    protected static $adminApi;

    /**
     * @var UploadApi
     */
    protected static $uploadApi;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_UPLOAD_PRESET = 'upload_preset_' . self::$UNIQUE_TEST_ID;

        $config = ConfigUtils::parseCloudinaryUrl(getenv(Configuration::CLOUDINARY_URL_ENV_VAR));
        $config = array_merge(self::TEST_LOGGING, $config);
        Configuration::instance()->init($config);

        self::$adminApi  = new AdminApi();
        self::$uploadApi = new UploadApi();
    }

    /**
     * Should a certain add on be tested?
     *
     * @param string $addOn
     *
     * @return bool
     */
    protected static function shouldTestAddOn($addOn)
    {
        $cldTestAddOns = strtolower(getenv('CLD_TEST_ADDONS'));
        if ($cldTestAddOns === Addon::ALL) {
            return true;
        }

        return ArrayUtils::inArrayI($addOn, explode(',', $cldTestAddOns));
    }

    /**
     * Should a certain feature be tested?
     *
     * @param string $feature The feature to test.
     *
     * @return bool
     */
    protected static function shouldTestFeature($feature)
    {
        $cldTestFeatures = strtolower(getenv('CLD_TEST_FEATURES'));
        if ($cldTestFeatures === Feature::ALL) {
            return true;
        }

        return ArrayUtils::inArrayI($feature, explode(',', $cldTestFeatures));
    }

    /**
     * @return bool
     */
    protected static function shouldRunDestructiveTests()
    {
        $cldRunDestructiveTests = strtolower(getenv('CLD_RUN_DESTRUCTIVE_TESTS'));

        return $cldRunDestructiveTests === 'yes';
    }

    /**
     * Create assets used for testing (uploading optional).
     *
     * Sample usage:
     *
     *   self::createTestAssets(
     *       [
     *           'test_rename_source',
     *           'test_rename_target' => ['upload' => false],
     *           'test_tagging_raw' => [
     *               'cleanup' => true,
     *               'options' => ['resource_type' => 'raw', 'tags' => ['foo', 'bar'], 'file' => $raw],
     *           ],
     *       ]
     *   );
     *
     * @param array  $assets Test assets to create.
     * @param string $prefix Prefix for the public id (defaults to test class name).
     *
     * @throws ApiError
     */
    protected static function createTestAssets($assets = [], $prefix = null)
    {
        foreach ($assets as $key => $values) {
            $key          = is_array($values) ? $key : $values;
            $publicId     = self::getUniquePublicId($key, $prefix);
            $options      = ArrayUtils::get($values, 'options', []);
            $assetOptions = ['public_id' => $publicId];
            $assetOptions = is_array($options) ? array_merge($assetOptions, $options) : $assetOptions;
            $assetType    = ArrayUtils::get($assetOptions, AssetType::KEY, AssetType::IMAGE);
            $file         = ArrayUtils::get($assetOptions, 'file');
            $upload       = ArrayUtils::get($values, 'upload', true);

            $asset = null;
            if ($upload && $assetType === AssetType::IMAGE) {
                $asset = self::uploadTestAssetImage($assetOptions, $file);
            } elseif ($upload && $assetType === AssetType::RAW) {
                $asset = self::uploadTestAssetFile($assetOptions);
            } elseif ($upload && $assetType === AssetType::VIDEO) {
                $asset = self::uploadTestAssetVideo($assetOptions);
            }

            self::addAssetToTestAssetsList(
                $asset,
                $assetOptions,
                ArrayUtils::get($values, 'cleanup', false),
                $key
            );
        }
    }

    /**
     * Upload a test asset
     *
     * @param string $file
     * @param array  $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    private static function uploadTestAsset($file, $options = [])
    {
        $options['tags'] = isset($options['tags']) && is_array($options['tags'])
            ? array_merge(self::$ASSET_TAGS, $options['tags'])
            : self::$ASSET_TAGS;
        $asset           = self::$uploadApi->upload($file, $options);

        self::assertValidAsset(
            $asset,
            [
                DeliveryType::KEY => isset($options[DeliveryType::KEY])
                    ? $options[DeliveryType::KEY]
                    : DeliveryType::UPLOAD,
                AssetType::KEY    => $options[AssetType::KEY],
                'tags'            => $options['tags'],
            ]
        );

        return $asset;
    }

    /**
     * Upload a test image
     *
     * @param array  $options
     * @param string $file
     *
     * @return ApiResponse
     * @throws ApiError
     */
    protected static function uploadTestAssetImage($options = [], $file = null)
    {
        $options[AssetType::KEY] = AssetType::IMAGE;
        $file                    = $file !== null ? $file : self::TEST_BASE64_IMAGE;

        return self::uploadTestAsset($file, $options);
    }

    /**
     * Upload a test file
     *
     * @param array $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    protected static function uploadTestAssetFile($options = [])
    {
        $options[AssetType::KEY] = AssetType::RAW;

        return self::uploadTestAsset(self::TEST_DOCX_PATH, $options);
    }

    /**
     * Upload a test video
     *
     * @param array $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    protected static function uploadTestAssetVideo($options = [])
    {
        $options[AssetType::KEY] = AssetType::VIDEO;

        return self::uploadTestAsset(self::TEST_ASSETS_DIR . 'sample.mp4', $options);
    }

    /**
     * Adds an asset to the list of test assets.
     *
     * @param ApiResponse|null $asset   A test asset.
     * @param array            $options Additional details to save alongside test asset.
     * @param bool             $cleanup A boolean indicating whether an asset should be deleted directly by public id
     *                                  during cleanup (this is useful, for example, for assets which do not contain the
     *                                  test tag).
     * @param string           $key     A key to save the test asset under (defaults to the asset's public_id).
     */
    private static function addAssetToTestAssetsList($asset, $options = [], $cleanup = false, $key = null)
    {
        $key = $key ?: ArrayUtils::get((array)$asset, 'public_id');

        if ($key) {
            self::$TEST_ASSETS[$key] = ['asset' => $asset, 'options' => $options, 'cleanup' => $cleanup];
        }
    }

    /**
     * Return an uploaded asset.
     *
     * @param string $name The key used to save the test asset.
     *
     * @return ApiResponse|null
     */
    protected static function getTestAsset($name)
    {
        return isset(self::$TEST_ASSETS[$name]['asset']) ? self::$TEST_ASSETS[$name]['asset'] : null;
    }

    /**
     * Return a public id of a test asset.
     *
     * @param string $name The key used to save the test asset.
     *
     * @return string|null
     */
    protected static function getTestAssetPublicId($name)
    {
        return self::getTestAssetProperty($name, 'public_id');
    }

    /**
     * Return an asset id of a test asset.
     *
     * @param string $name The key used to save the test asset.
     *
     * @return string|null
     */
    protected static function getTestAssetAssetId($name)
    {
        return self::getTestAssetProperty($name, 'asset_id');
    }

    /**
     * Return a property of a test asset.
     *
     * @param string $assetName    The key used to save the test asset.
     * @param string $propertyName The name of the property of the test asset.
     *
     * @return string|null
     */
    protected static function getTestAssetProperty($assetName, $propertyName)
    {
        if (! self::$TEST_ASSETS[$assetName]) {
            return null;
        }

        if (self::$TEST_ASSETS[$assetName]['asset']) {
            return self::$TEST_ASSETS[$assetName]['asset'][$propertyName];
        }

        return self::$TEST_ASSETS[$assetName]['options'][$propertyName];
    }

    /**
     * Get a unique public id.
     *
     * @param string $name   The name to generate the public id.
     * @param string $prefix The prefix for the public id (defaults to the test's class name).
     *
     * @return string
     */
    private static function getUniquePublicId($name, $prefix = null)
    {
        $prefix = $prefix !== null ? $prefix : (new ReflectionClass(static::class))->getShortName();

        return StringUtils::camelCaseToSnakeCase($prefix . '_' . $name . '_' . self::$UNIQUE_TEST_ID);
    }

    /**
     * Fetch remote asset
     *
     * @param       $assetId
     * @param array $options
     *
     * @return void
     */
    protected static function fetchRemoteTestAsset($assetId, $options = [])
    {
        $assetUrl = Media::fromParams($assetId, $options)->toUrl();

        $res = (new Client())->head($assetUrl);

        self::assertEquals(HttpStatusCode::OK, $res->getStatusCode());
    }

    /**
     * Adds an asset to a list for later deletion using `cleanupAssets()`.
     *
     * @param ApiResponse $asset
     * @param array       $options
     */
    protected static function addAssetToCleanupList($asset, $options = [])
    {
        self::addAssetToTestAssetsList($asset, $options, true);
    }

    /**
     * Assert that a given object is a valid asset detail object.
     * Optionally checks it against given values.
     *
     * @param array|object $asset
     * @param array        $values
     */
    protected static function assertValidAsset($asset, $values = [])
    {
        $deliveryType = ArrayUtils::get($values, DeliveryType::KEY, DeliveryType::UPLOAD);
        $assetType    = ArrayUtils::get($values, AssetType::KEY, AssetType::IMAGE);

        self::assertEquals($deliveryType, $asset[DeliveryType::KEY]);
        self::assertEquals($assetType, $asset[AssetType::KEY]);
        self::assertObjectStructure(
            $asset,
            [
                'public_id'  => IsType::TYPE_STRING,
                'created_at' => IsType::TYPE_STRING,
                'url'        => IsType::TYPE_STRING,
                'secure_url' => IsType::TYPE_STRING,
                'bytes'      => IsType::TYPE_INT,
            ]
        );

        if ($deliveryType === DeliveryType::FACEBOOK || $assetType === AssetType::RAW) {
            self::assertArrayNotHasKey('height', $asset);
            self::assertArrayNotHasKey('width', $asset);
        } elseif (in_array($assetType, [AssetType::IMAGE, AssetType::VIDEO], true)) {
            self::assertObjectStructure(
                $asset,
                [
                    'width'  => IsType::TYPE_INT,
                    'height' => IsType::TYPE_INT,
                    'format' => IsType::TYPE_STRING,
                ]
            );
        } elseif ($assetType === AssetType::IMAGE) {
            self::assertObjectStructure($asset, ['placeholder' => IsType::TYPE_BOOL]);
        } elseif ($assetType === AssetType::VIDEO) {
            self::assertObjectStructure(
                $asset,
                [
                    'audio'      => IsType::TYPE_ARRAY,
                    'video'      => IsType::TYPE_ARRAY,
                    'frame_rate' => IsType::TYPE_FLOAT,
                    'duration'   => IsType::TYPE_FLOAT,
                    'bit_rate'   => IsType::TYPE_INT,
                    'rotation'   => IsType::TYPE_INT,
                    'nb_frames'  => IsType::TYPE_INT,
                ]
            );
        }

        if ($deliveryType !== DeliveryType::PRIVATE_DELIVERY) {
            $format = ! empty($asset['format']) ? $asset['format'] : '';

            self::assertAssetUrl($asset, 'url', $format, $deliveryType, $assetType);
            self::assertAssetUrl($asset, 'secure_url', $format, $deliveryType, $assetType);
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $asset[$key]);
        }
    }

    /**
     * Assert that a given object is a valid asset detail archive
     * Optionally checks it against given values.
     *
     * @param array|object $archive
     * @param string       $format
     * @param array        $values
     */
    protected static function assertValidArchive($archive, $format = 'zip', $values = [])
    {
        self::assertValidAsset(
            $archive,
            array_merge(
                [
                    DeliveryType::KEY => DeliveryType::UPLOAD,
                    AssetType::KEY    => AssetType::RAW,
                ],
                $values
            )
        );
        self::assertObjectStructure(
            $archive,
            [
                'tags'           => IsType::TYPE_ARRAY,
                'bytes'          => IsType::TYPE_INT,
                'resource_count' => IsType::TYPE_INT,
                'file_count'     => IsType::TYPE_INT,
            ]
        );
        self::assertMatchesRegularExpression('/\.' . $format . '$/', $archive['url']);
    }

    /**
     * Assert that a given array is a valid transformation representation
     * Optionally checks it against given values.
     *
     * @param array|object $asset
     * @param array        $values
     */
    protected static function assertValidTransformationRepresentation($asset, $values = [])
    {
        self::assertObjectStructure(
            $asset,
            [
                'transformation' => IsType::TYPE_STRING,
                'width'          => IsType::TYPE_INT,
                'height'         => IsType::TYPE_INT,
                'bytes'          => IsType::TYPE_INT,
                'format'         => IsType::TYPE_STRING,
                'url'            => IsType::TYPE_STRING,
                'secure_url'     => IsType::TYPE_STRING,
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $asset[$key]);
        }

        self::assertNotEmpty($asset['url']);
        self::assertNotEmpty($asset['secure_url']);
    }

    /**
     * Assert that a given array is a valid derived asset.
     * Optionally checks it against given values.
     *
     * @param array|object $asset
     * @param array        $values
     */
    protected static function assertValidDerivedAsset($asset, $values = [])
    {
        self::assertObjectStructure(
            $asset,
            [
                'transformation' => IsType::TYPE_STRING,
                'id'             => IsType::TYPE_STRING,
                'bytes'          => IsType::TYPE_INT,
                'format'         => IsType::TYPE_STRING,
                'url'            => IsType::TYPE_STRING,
                'secure_url'     => IsType::TYPE_STRING,
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $asset[$key]);
        }

        self::assertNotEmpty($asset['url']);
        self::assertNotEmpty($asset['secure_url']);
    }

    /**
     * Assert that a given array is a valid data of single animated image
     * Optionally checks it against given values.
     *
     * @param array|object $resource
     * @param array        $values
     */
    protected static function assertValidMulti($resource, $values = [])
    {
        self::assertObjectStructure(
            $resource,
            [
                'url'        => IsType::TYPE_STRING,
                'secure_url' => IsType::TYPE_STRING,
                'version'    => IsType::TYPE_INT,
                'public_id'  => IsType::TYPE_STRING,
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $resource[$key]);
        }
    }

    /**
     * Assert that a given array is a valid sprite
     * Optionally checks it against given values.
     *
     * @param array|object $resource
     * @param array        $values
     */
    protected static function assertValidSprite($resource, $values = [])
    {
        self::assertObjectStructure(
            $resource,
            [
                'css_url'          => IsType::TYPE_STRING,
                'image_url'        => IsType::TYPE_STRING,
                'json_url'         => IsType::TYPE_STRING,
                'secure_css_url'   => IsType::TYPE_STRING,
                'secure_image_url' => IsType::TYPE_STRING,
                'secure_json_url'  => IsType::TYPE_STRING,
                'version'          => IsType::TYPE_INT,
                'public_id'        => IsType::TYPE_STRING,
                'image_infos'      => IsType::TYPE_ARRAY,
            ]
        );

        foreach ($resource['image_infos'] as $imageInfo) {
            self::assertObjectStructure(
                $imageInfo,
                [
                    'width'  => IsType::TYPE_INT,
                    'height' => IsType::TYPE_INT,
                    'x'      => IsType::TYPE_INT,
                    'y'      => IsType::TYPE_INT,
                ]
            );
            self::assertNotEmpty($imageInfo['width']);
            self::assertNotEmpty($imageInfo['height']);
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $resource[$key]);
        }
    }

    /**
     * Assert that a given object contains a valid asset url
     *
     * @param array|object $asset
     * @param string       $field
     * @param string       $format
     * @param string       $deliveryType
     * @param string       $assetType
     */
    protected static function assertAssetUrl(
        $asset,
        $field,
        $format = '',
        $deliveryType = DeliveryType::UPLOAD,
        $assetType = AssetType::IMAGE
    ) {
        $media = new Media($asset['public_id']);
        $media->secure(strpos($field, 'secure_') === 0)->assetType($assetType)->deliveryType($deliveryType);
        if (! empty($asset['version'])) {
            $media->version($asset['version']);
        }
        if (! empty($format)) {
            $media->extension($format);
        }

        $assetUrl    = parse_url($asset[$field]);
        $expectedUrl = parse_url($media->toUrl());

        self::assertEquals(
            $expectedUrl['scheme'],
            $assetUrl['scheme'],
            "The object's \"$field\" field contains a URL with a scheme that is different than expected."
        );

        self::assertEquals(
            $expectedUrl['path'],
            $assetUrl['path'],
            "The object's \"$field\" field contains a URL with a path that is different than expected."
        );
    }

    /**
     * Assert that a given url contains a valid path and values.
     *
     * @param string $assetUrl
     * @param string $prefixUrl
     * @param string $path
     * @param array  $values
     */
    protected static function assertDownloadSignUrl($assetUrl, $prefixUrl = null, $path = null, $values = [])
    {
        $parseUrl = parse_url($assetUrl);
        $query    = self::parseHttpQuery($parseUrl['query']);

        self::assertArrayHasKey('timestamp', $query);
        self::assertArrayHasKey('signature', $query);
        if ($prefixUrl) {
            self::assertEquals($prefixUrl, $parseUrl['scheme'] . '://' . $parseUrl['host']);
        }
        if ($path) {
            self::assertEquals($path, $parseUrl['path']);
        }

        foreach ($values as $key => $value) {
            self::assertSame($value, $query[$key]);
        }
    }

    /**
     * Assert that a given asset was deleted based on a given ApiResponse for a deletion action
     *
     * @param ApiResponse $result
     * @param string      $publicId
     * @param int         $deletedCount
     * @param int         $originalCount
     * @param int         $derivedCount
     * @param int         $notFoundCount
     */
    protected static function assertAssetDeleted(
        $result,
        $publicId,
        $deletedCount = 1,
        $originalCount = 1,
        $derivedCount = 0,
        $notFoundCount = 0
    ) {
        $groupResult = array_count_values($result['deleted']);

        self::assertEquals($deletedCount, $groupResult['deleted']);
        self::assertEquals('deleted', $result['deleted'][$publicId]);
        self::assertCount($deletedCount + $notFoundCount, $result['deleted_counts']);
        self::assertEquals($originalCount, $result['deleted_counts'][$publicId]['original']);
        self::assertEquals($derivedCount, $result['deleted_counts'][$publicId]['derived']);
        if ($notFoundCount) {
            self::assertEquals($notFoundCount, $groupResult['not_found']);
        }
    }

    /**
     * Assert that a given object is a valid upload preset
     * Optionally checks it against given values.
     *
     * @param array|object $uploadPreset
     * @param array        $values
     * @param array        $settings
     */
    protected static function assertValidUploadPreset($uploadPreset, $values = [], $settings = [])
    {
        self::assertNotEmpty($uploadPreset);
        self::assertObjectStructure(
            $uploadPreset,
            [
                'name'     => IsType::TYPE_STRING,
                'unsigned' => IsType::TYPE_BOOL,
                'settings' => IsType::TYPE_ARRAY,
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $uploadPreset[$key]);
        }

        foreach ($settings as $key => $value) {
            self::assertEquals($value, $uploadPreset['settings'][$key]);
        }
    }

    /**
     * Assert that a given object is a valid result given from creating an upload preset
     *
     * @param $result
     */
    protected static function assertUploadPresetCreation($result)
    {
        self::assertEquals('created', $result['message']);
        self::assertNotEmpty($result['name']);
        self::assertObjectStructure($result, ['name' => IsType::TYPE_STRING]);
    }

    /**
     * Assert that a given object is a valid folder object.
     * Optionally checks it against given values.
     *
     * @param array|object $folder
     * @param array        $values
     */
    protected static function assertValidFolder($folder, $values = [])
    {
        self::assertObjectStructure(
            $folder,
            ['name' => IsType::TYPE_STRING, 'path' => IsType::TYPE_STRING]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $folder[$key]);
        }
    }

    /**
     * Assert that a given object is a Streaming Profile details object
     * Optionally checks it against given values.
     *
     * @param array|object $streamingProfile
     * @param array        $values
     */
    protected static function assertValidStreamingProfile($streamingProfile, $values = [])
    {
        self::assertNotEmpty($streamingProfile['data']);

        // Verify basic object structure
        self::assertObjectStructure(
            $streamingProfile['data'],
            [
                'display_name'    => [IsType::TYPE_STRING, IsType::TYPE_NULL],
                'name'            => IsType::TYPE_STRING,
                'predefined'      => IsType::TYPE_BOOL,
                'representations' => IsType::TYPE_ARRAY,
            ]
        );

        // Verify required fields exist
        self::assertNotEmpty($streamingProfile['data']['name']);
        self::assertNotEmpty($streamingProfile['data']['representations']);
        self::assertNotEmpty($streamingProfile['data']['representations'][0]['transformation']);

        // Compare given values to actual values in streaming profile
        foreach ($values as $key => $value) {
            self::assertEquals($value, $streamingProfile['data'][$key]);
        }
    }

    /**
     * Asserts that a given object is a Transformation detail object.
     * Optionally checks it against given values.
     *
     * @param array|object $transformation
     * @param array        $values
     * @param array        $transformationInfo
     */
    protected static function assertValidTransformation($transformation, $values = [], $transformationInfo = [])
    {
        self::assertObjectStructure(
            $transformation,
            [
                'allowed_for_strict' => IsType::TYPE_BOOL,
                'used'               => IsType::TYPE_BOOL,
                'named'              => IsType::TYPE_BOOL,
                'name'               => IsType::TYPE_STRING,
            ]
        );
        self::assertNotEmpty($transformation['name']);

        foreach ($values as $key => $value) {
            self::assertEquals($value, $transformation[$key]);
        }

        if ($transformationInfo) {
            self::assertArrayContainsArray($transformation['info'], $transformationInfo);
        }
    }

    /**
     * Assert that a given object is an upload mapping object
     * Optionally checks it against given values.
     *
     * @param array|object $uploadMapping
     * @param array        $values
     * @param string       $message
     */
    protected static function assertValidUploadMapping($uploadMapping, $values = [], $message = '')
    {
        self::assertObjectStructure(
            $uploadMapping,
            ['template' => IsType::TYPE_STRING, 'folder' => IsType::TYPE_STRING],
            $message
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $uploadMapping[$key]);
        }
    }

    /**
     * Creates upload preset
     *
     * @param array $options
     *
     * @return ApiResponse
     */
    protected static function createUploadPreset($options = [])
    {
        $result = self::$adminApi->createUploadPreset($options);

        self::assertUploadPresetCreation($result);

        return $result;
    }


    /**
     * Cleanup all assets marked for cleanup in the TEST_ASSETS stack.
     */
    protected static function cleanupMarkedTestAssets()
    {
        foreach (self::$TEST_ASSETS as $key => $asset) {
            if ($asset['cleanup']) {
                self::cleanupAsset($asset['asset']['public_id'], $asset['options']);
                unset(self::$TEST_ASSETS[$key]);
            }
        }
    }

    /**
     * Delete an asset by tag
     *
     * Try to delete an asset if deletion fails log the error
     *
     * @param string $tag
     * @param array  $options
     */
    protected static function cleanupAssetsByTag($tag, $options = [])
    {
        self::cleanupSoftly(
            'deleteAssetsByTag',
            'Asset with a tag ' . $tag . ' deletion failed during teardown',
            static function ($result) use ($tag) {
                return ! isset($result['deleted'][$tag]) || $result['deleted'][$tag] !== 'deleted';
            },
            $tag,
            $options
        );
    }

    /**
     * Delete asset
     *
     * Try to delete asset if deletion fails log the error
     *
     * @param string $publicId
     * @param array  $options
     */
    protected static function cleanupAsset($publicId, $options = [])
    {
        self::cleanupSoftly(
            'deleteAssets',
            'Asset ' . $publicId . ' deletion failed during teardown',
            static function ($result) use ($publicId) {
                return ! isset($result['deleted'][$publicId]) || $result['deleted'][$publicId] !== 'deleted';
            },
            $publicId,
            $options
        );
    }

    /**
     * Delete assets created for tests.
     *
     * 1. Will directly delete all assets marked for cleanup.
     * 2. Will delete all assets with the test tag of the given asset types (defaults to image)
     *
     * @param array $assetTypes An array of asset types to delete (defaults to image)
     */
    protected static function cleanupTestAssets($assetTypes = [AssetType::IMAGE])
    {
        self::cleanupMarkedTestAssets();

        foreach ($assetTypes as $assetType) {
            self::cleanupAssetsByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => $assetType]);
        }
    }

    /**
     * Delete a folder
     *
     * Try to delete a folder if deletion fails log the error
     *
     * @param string $path
     */
    protected static function cleanupFolder($path)
    {
        self::cleanupSoftly(
            'deleteFolder',
            'Folder ' . $path . ' deletion failed during teardown',
            static function ($result) use ($path) {
                return ! isset($result['deleted']) || ! in_array($path, $result['deleted'], true);
            },
            $path
        );
    }

    /**
     * Delete a transformation
     *
     * Try to delete a transformation if deletion fails log the error
     *
     * @param string|array $transformation
     * @param array        $options
     */
    protected static function cleanupTransformation($transformation, $options = [])
    {
        self::cleanupSoftly(
            'deleteTransformation',
            'Transformation ' . $transformation . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'deleted';
            },
            $transformation,
            $options
        );
    }

    /**
     * Delete a streaming profile
     *
     * Try to delete a streaming profile if deletion fails log the error
     *
     * @param string $name
     */
    protected static function cleanupStreamingProfile($name)
    {
        self::cleanupSoftly(
            'deleteStreamingProfile',
            'Streaming profile ' . $name . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'deleted';
            },
            $name
        );
    }

    /**
     * Delete an upload mapping
     *
     * Try to delete an upload mapping if deletion fails log the error
     *
     * @param string $name
     */
    protected static function cleanupUploadMapping($name)
    {
        self::cleanupSoftly(
            'deleteUploadMapping',
            'Upload mapping ' . $name . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'deleted';
            },
            $name
        );
    }

    /**
     * Delete an upload preset
     *
     * Try to delete an upload preset if deletion fails log the error
     *
     * @param string $name
     */
    protected static function cleanupUploadPreset($name)
    {
        self::cleanupSoftly(
            'deleteUploadPreset',
            'Upload preset ' . $name . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'deleted';
            },
            $name
        );
    }

    /**
     * Delete a metadata field.
     *
     * Try to delete a metadata field if deletion fails log the error.
     *
     * @param string $fieldId
     */
    protected static function cleanupMetadataField($fieldId)
    {
        self::cleanupSoftly(
            'deleteMetadataField',
            'Metadata field ' . $fieldId . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'deleted';
            },
            $fieldId
        );
    }

    /**
     * @param string|array $function
     * @param string       $message
     * @param callable     $invalidResult
     */
    private static function cleanupSoftly($function, $message, $invalidResult)
    {
        $args = array_slice(func_get_args(), 3);
        try {
            $result = call_user_func_array(
                is_array($function) ? $function : [self::$adminApi, $function],
                $args
            );
            if ($invalidResult($result)) {
                throw new RuntimeException($message);
            }
        } catch (Exception $e) {
            //@TODO: Use logger to print ERROR message
        }
    }

    /**
     * Fixes a query string decoding.
     *
     * parse_query decodes a query string:
     *   keys[]=value1&keys[]=value2
     * as:
     *   ['keys[]' => ['value1', 'value2']]
     *
     * This method parse a given query string and deletes brackets in array keys, so the result would look like:
     *   ['keys' => ['value1', 'value2']]
     *
     * @param $httpQuery
     *
     * @return array
     */
    private static function parseHttpQuery($httpQuery)
    {
        $query = Psr7\Query::parse($httpQuery);

        foreach ($query as $key => $value) {
            if (is_array($value) && strpos($key, '[]') === strlen($key) - 2) {
                unset($query[$key]);
                $query[substr($key, 0, -2)] = $value;
            }
        }

        return $query;
    }
}
