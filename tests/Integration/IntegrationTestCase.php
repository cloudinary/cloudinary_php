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
use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Media;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ConfigUtils;
use Cloudinary\Test\CloudinaryTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Exception;
use GuzzleHttp\Client;
use PHPUnit_Framework_Constraint_IsType as IsType;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Teapot\StatusCode;

/**
 * Class IntegrationTestCase
 */
abstract class IntegrationTestCase extends CloudinaryTestCase
{
    const TEST_ASSETS_DIR = __DIR__ . '/../assets/';
    const TEST_IMAGE_PATH = self::TEST_ASSETS_DIR . AssetTestCase::IMAGE_NAME;
    const TEST_IMAGE_GIF_PATH = self::TEST_ASSETS_DIR . AssetTestCase::IMAGE_NAME_GIF;
    const TEST_BASE64_IMAGE = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    const TEST_LOGGING = ['logging' => ['test' => ['level' => 'debug']]];
    const TEST_EVAL_STR = 'if (resource_info["width"] < 450) { upload_options["tags"] = "a,b" }; ' .
                          'upload_options["context"] = "width=" + resource_info["width"]';
    const TEST_EVAL_TAGS_RESULT = ['a', 'b'];

    private static $RESOURCES_STACK = [];

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

        self::$adminApi = new AdminApi();
        self::$uploadApi = new UploadApi();
    }

    /**
     * Adds details of a resource to a list for later deletion using `cleanupResources()`
     *
     * @param string $public_id
     * @param array  $options
     */
    protected static function addResourceToCleanupList($public_id, $options = [])
    {
        self::$RESOURCES_STACK[] = ['public_id' => $public_id, 'options' => $options];
    }

    /**
     * Cleanup all resources in a stack
     */
    protected static function cleanupResources()
    {
        foreach (self::$RESOURCES_STACK as $key => $resource) {
            self::cleanupResource($resource['public_id'], $resource['options']);
            unset(self::$RESOURCES_STACK[$key]);
        }
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
        if ($cldTestAddOns === 'all') {
            return true;
        }
        return ArrayUtils::inArrayI($addOn, explode(',', $cldTestAddOns));
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
     * Upload a test resource
     *
     * @param string $file
     * @param array  $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    private static function uploadTestResource($file, $options = [])
    {
        $options['tags'] = isset($options['tags']) && is_array($options['tags'])
            ? array_merge(self::$ASSET_TAGS, $options['tags'])
            : self::$ASSET_TAGS;
        $resource = self::$uploadApi->upload($file, $options);

        self::assertValidResource(
            $resource,
            [
                DeliveryType::KEY => isset($options[DeliveryType::KEY])
                    ? $options[DeliveryType::KEY]
                    : DeliveryType::UPLOAD,
                AssetType::KEY => $options[AssetType::KEY],
                'tags' => $options['tags']
            ]
        );

        return $resource;
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
    protected static function uploadTestResourceImage($options = [], $file = null)
    {
        $options[AssetType::KEY] = AssetType::IMAGE;
        $file = $file !== null ? $file : self::TEST_BASE64_IMAGE;

        return self::uploadTestResource($file, $options);
    }

    /**
     * Upload a test file
     *
     * @param array $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    protected static function uploadTestResourceFile($options = [])
    {
        $options[AssetType::KEY] = AssetType::RAW;

        return self::uploadTestResource(self::TEST_ASSETS_DIR . 'sample.docx', $options);
    }

    /**
     * Upload a test video
     *
     * @param array $options
     *
     * @return ApiResponse
     * @throws ApiError
     */
    protected static function uploadTestResourceVideo($options = [])
    {
        $options[AssetType::KEY] = AssetType::VIDEO;

        return self::uploadTestResource(self::TEST_ASSETS_DIR . 'sample.mp4', $options);
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

        self::assertEquals(StatusCode::OK, $res->getStatusCode());
    }

    /**
     * Assert that a given object is a valid resource detail object.
     * Optionally checks it against given values.
     *
     * @param array|object $resource
     * @param array        $values
     */
    protected static function assertValidResource($resource, $values = [])
    {
        $deliveryType = ArrayUtils::get($values, DeliveryType::KEY, DeliveryType::UPLOAD);
        $assetType = ArrayUtils::get($values, AssetType::KEY, AssetType::IMAGE);

        self::assertEquals($deliveryType, $resource[DeliveryType::KEY]);
        self::assertEquals($assetType, $resource[AssetType::KEY]);
        self::assertObjectStructure(
            $resource,
            [
                'public_id' => IsType::TYPE_STRING,
                'created_at' => IsType::TYPE_STRING,
                'url' => IsType::TYPE_STRING,
                'secure_url' => IsType::TYPE_STRING,
                'bytes' => IsType::TYPE_INT,
            ]
        );

        if ($deliveryType === DeliveryType::FACEBOOK || $assetType === AssetType::RAW) {
            self::assertArrayNotHasKey('height', $resource);
            self::assertArrayNotHasKey('width', $resource);
        } elseif (in_array($assetType, [AssetType::IMAGE, AssetType::VIDEO], true)) {
            self::assertObjectStructure(
                $resource,
                [
                    'width' => IsType::TYPE_INT,
                    'height' => IsType::TYPE_INT,
                    'format' => IsType::TYPE_STRING
                ]
            );
        } elseif ($assetType === AssetType::IMAGE) {
            self::assertObjectStructure($resource, ['placeholder' => IsType::TYPE_BOOL]);
        } elseif ($assetType === AssetType::VIDEO) {
            self::assertObjectStructure(
                $resource,
                [
                    'audio' => IsType::TYPE_ARRAY,
                    'video' => IsType::TYPE_ARRAY,
                    'frame_rate' => IsType::TYPE_FLOAT,
                    'duration' => IsType::TYPE_FLOAT,
                    'bit_rate' => IsType::TYPE_INT,
                    'rotation' => IsType::TYPE_INT,
                    'nb_frames' => IsType::TYPE_INT
                ]
            );
        }

        if ($deliveryType !== DeliveryType::PRIVATE_DELIVERY) {
            $format = !empty($resource['format']) ? $resource['format'] : '';

            self::assertResourceUrl($resource, 'url', $format, $deliveryType, $assetType);
            self::assertResourceUrl($resource, 'secure_url', $format, $deliveryType, $assetType);
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $resource[$key]);
        }
    }

    /**
     * Assert that a given object is a valid resource detail archive
     * Optionally checks it against given values.
     *
     * @param array|object $archive
     * @param string       $format
     * @param array        $values
     */
    protected static function assertValidArchive($archive, $format = 'zip', $values = [])
    {
        self::assertValidResource(
            $archive,
            array_merge(
                [
                    DeliveryType::KEY => DeliveryType::UPLOAD,
                    AssetType::KEY => AssetType::RAW
                ],
                $values
            )
        );
        self::assertObjectStructure(
            $archive,
            [
                'tags' => IsType::TYPE_ARRAY,
                'bytes' => IsType::TYPE_INT,
                'resource_count' => IsType::TYPE_INT,
                'file_count' => IsType::TYPE_INT
            ]
        );
        self::assertRegexp('/\.' . $format . '$/', $archive['url']);
    }

    /**
     * Assert that a given array is a valid transformation representation
     * Optionally checks it against given values.
     *
     * @param array|object $resource
     * @param array        $values
     */
    protected static function assertValidTransformationRepresentation($resource, $values = [])
    {
        self::assertObjectStructure(
            $resource,
            [
                'transformation' => IsType::TYPE_STRING,
                'width' => IsType::TYPE_INT,
                'height' => IsType::TYPE_INT,
                'bytes' => IsType::TYPE_INT,
                'format' => IsType::TYPE_STRING,
                'url' => IsType::TYPE_STRING,
                'secure_url' => IsType::TYPE_STRING
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $resource[$key]);
        }

        self::assertNotEmpty($resource['url']);
        self::assertNotEmpty($resource['secure_url']);
    }

    /**
     * Assert that a given array is a valid derived resource.
     * Optionally checks it against given values.
     *
     * @param array|object $resource
     * @param array        $values
     */
    protected static function assertValidDerivedResource($resource, $values = [])
    {
        self::assertObjectStructure(
            $resource,
            [
                'transformation' => IsType::TYPE_STRING,
                'id' => IsType::TYPE_STRING,
                'bytes' => IsType::TYPE_INT,
                'format' => IsType::TYPE_STRING,
                'url' => IsType::TYPE_STRING,
                'secure_url' => IsType::TYPE_STRING
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $resource[$key]);
        }

        self::assertNotEmpty($resource['url']);
        self::assertNotEmpty($resource['secure_url']);
    }

    /**
     * Assert that a given object contains a valid resource url
     *
     * @param array|object $resource
     * @param string       $field
     * @param string       $format
     * @param string       $deliveryType
     * @param string       $assetType
     */
    protected static function assertResourceUrl(
        $resource,
        $field,
        $format = '',
        $deliveryType = DeliveryType::UPLOAD,
        $assetType = AssetType::IMAGE
    ) {
        $media = new Media($resource['public_id']);
        $media->secure(strpos($field, 'secure_') === 0)->assetType($assetType)->deliveryType($deliveryType);
        if (!empty($resource['version'])) {
            $media->version($resource['version']);
        }
        if (!empty($format)) {
            $media->extension($format);
        }

        $resourceUrl = parse_url($resource[$field]);
        $expectedUrl = parse_url($media->toUrl());

        self::assertEquals(
            $expectedUrl['scheme'],
            $resourceUrl['scheme'],
            "The object's \"$field\" field contains a URL with a scheme that is different than expected."
        );

        self::assertEquals(
            $expectedUrl['path'],
            $resourceUrl['path'],
            "The object's \"$field\" field contains a URL with a path that is different than expected."
        );
    }

    /**
     * Assert that a given resource was deleted based on a given ApiResponse for a deletion action
     *
     * @param ApiResponse $result
     * @param string      $publicId
     * @param int         $deletedCount
     * @param int         $originalCount
     * @param int         $derivedCount
     * @param int         $notFoundCount
     */
    protected static function assertResourceDeleted(
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
                'name' => IsType::TYPE_STRING,
                'unsigned' => IsType::TYPE_BOOL,
                'settings' => IsType::TYPE_ARRAY
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
                'display_name' => [IsType::TYPE_STRING, IsType::TYPE_NULL],
                'name' => IsType::TYPE_STRING,
                'predefined' => IsType::TYPE_BOOL,
                'representations' => IsType::TYPE_ARRAY
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
                'used' => IsType::TYPE_BOOL,
                'named' => IsType::TYPE_BOOL,
                'name' => IsType::TYPE_STRING
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
     * Assert that a request was made to the correct url.
     *
     * @param RequestInterface $request
     * @param string           $path
     * @param string           $message
     */
    protected static function assertRequestUrl(RequestInterface $request, $path, $message = '')
    {
        $config = Configuration::instance();

        self::assertEquals(
            '/' . ApiClient::apiVersion() . '/' . $config->account->cloudName . $path,
            $request->getUri()->getPath(),
            $message
        );
    }

    /**
     * Assert the HTTP request method is GET.
     *
     * @param RequestInterface $request
     * @param string           $message
     */
    protected static function assertRequestGet(RequestInterface $request, $message = 'HTTP method should be GET')
    {
        self::assertEquals('GET', $request->getMethod(), $message);
    }

    /**
     * Assert the HTTP request method is POST.
     *
     * @param RequestInterface $request
     * @param string           $message
     */
    protected static function assertRequestPost(RequestInterface $request, $message = 'HTTP method should be POST')
    {
        self::assertEquals('POST', $request->getMethod(), $message);
    }

    /**
     * Assert the HTTP request method is DELETE.
     *
     * @param RequestInterface $request
     * @param string           $message
     */
    protected static function assertRequestDelete(RequestInterface $request, $message = 'HTTP method should be DELETE')
    {
        self::assertEquals('DELETE', $request->getMethod(), $message);
    }

    /**
     * Asserts that a request contains the expected fields and values.
     *
     * @param RequestInterface $request
     * @param array|null       $fields
     * @param string           $message
     */
    protected static function assertRequestFields(RequestInterface $request, $fields = null, $message = '')
    {
        self::assertEquals(
            json_decode($request->getBody()->getContents(), true),
            $fields,
            $message
        );
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
     * Delete a resource by tag
     *
     * Try to delete a resource if deletion fails log the error
     *
     * @param string $tag
     * @param array  $options
     */
    protected static function cleanupResourcesByTag($tag, $options = [])
    {
        self::cleanupSoftly(
            'deleteResourcesByTag',
            'Resource with a tag ' . $tag . ' deletion failed during teardown',
            static function ($result) use ($tag) {
                return !isset($result['deleted'][$tag]) || $result['deleted'][$tag] !== 'deleted';
            },
            $tag,
            $options
        );
    }

    /**
     * Delete resource
     *
     * Try to delete resource if deletion fails log the error
     *
     * @param string $publicId
     * @param array  $options
     */
    protected static function cleanupResource($publicId, $options = [])
    {
        self::cleanupSoftly(
            'deleteResources',
            'Resource ' . $publicId . ' deletion failed during teardown',
            static function ($result) use ($publicId) {
                return !isset($result['deleted'][$publicId]) || $result['deleted'][$publicId] !== 'deleted';
            },
            $publicId,
            $options
        );
    }

    /**
     * Delete resource with UNIQUE_TEST_TAG
     *
     * Try to delete resource if deletion fails log the error
     *
     * @param string $assetType
     */
    protected static function cleanupTestResources($assetType = AssetType::IMAGE)
    {
        self::cleanupResourcesByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => $assetType]);
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
                return !isset($result['deleted']) || !in_array($path, $result['deleted'], true);
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
                return !isset($result['message']) || $result['message'] !== 'deleted';
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
                return !isset($result['message']) || $result['message'] !== 'deleted';
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
                return !isset($result['message']) || $result['message'] !== 'deleted';
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
                return !isset($result['message']) || $result['message'] !== 'deleted';
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
                return !isset($result['message']) || $result['message'] !== 'deleted';
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
}
