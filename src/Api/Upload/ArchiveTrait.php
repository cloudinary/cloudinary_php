<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Upload;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\ApiUtils;
use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Trait ArchiveTrait
 *
 * @property ApiClient $apiClient Defined in UploadApi class
 *
 * @api
 */
trait ArchiveTrait
{
    /**
     * @param $options
     *
     * @return array
     * @internal
     *
     * Returns an array of parameters used to create an archive
     *
     */
    public static function buildArchiveParams($options)
    {
        $simpleParams = [
            'allow_missing',
            'async',
            'expires_at',
            'flatten_folders',
            'flatten_transformations',
            'keep_derived',
            'mode',
            'notification_url',
            'phash',
            'skip_transformation_name',
            'target_format',
            'target_public_id',
            'target_tags',
            'timestamp',
            'type',
            'use_original_filename',
        ];


        $complexParams = [
            'prefixes'                   => ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'prefixes')),
            'public_ids'                 => ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'public_ids')),
            'fully_qualified_public_ids' => ApiUtils::serializeSimpleApiParam(
                ArrayUtils::get($options, 'fully_qualified_public_ids')
            ),
            'tags'                       => ApiUtils::serializeSimpleApiParam((ArrayUtils::get($options, 'tags'))),
            'target_tags'                => ApiUtils::serializeSimpleApiParam(
                (ArrayUtils::get($options, 'target_tags'))
            ),
            'transformations'            => ApiUtils::serializeAssetTransformations(
                ArrayUtils::get($options, 'transformations')
            ),
        ];

        return ApiUtils::finalizeUploadApiParams(
            array_merge(ArrayUtils::whitelist($options, $simpleParams), $complexParams)
        );
    }

    /**
     * Creates a new archive in the server and returns information in JSON format.
     *
     * @param array  $options
     * @param string $targetFormat
     *
     * @return PromiseInterface
     *
     */
    public function createArchiveAsync($options = [], $targetFormat = null)
    {
        $params = self::buildArchiveParams($options);
        ArrayUtils::addNonEmpty($params, 'target_format', $targetFormat);

        return $this->callUploadApiAsync(UploadEndPoint::GENERATE_ARCHIVE, $params, $options);
    }

    /**
     * Creates a new archive in the server and returns information in JSON format.
     *
     * @param array $options
     * @param null  $targetFormat
     *
     * @return ApiResponse
     *
     */
    public function createArchive($options = [], $targetFormat = null)
    {
        return $this->createArchiveAsync($options, $targetFormat)->wait();
    }

    /**
     * Creates a new zip archive in the server and returns information in JSON format.
     *
     * @param array $options
     *
     * @return PromiseInterface
     */
    public function createZipAsync($options = [])
    {
        return $this->createArchiveAsync($options, 'zip');
    }

    /**
     * Creates a new zip archive in the server and returns information in JSON format.
     *
     * @param array $options
     *
     * @return ApiResponse
     *
     */
    public function createZip($options = [])
    {
        return $this->createZipAsync($options)->wait();
    }

    /**
     * Returns a URL that when invoked creates an archive and returns it.
     *
     * @param array      $options                 Additional options. Can be one of the following:
     *
     * @var string       $resource_type           The resource type of files to include in the archive.
     *                                     Must be one of image | video | raw
     * @var string       $type                    The specific file delivery type of resources:
     *                                     upload|private|authenticated
     * @var string|array $tags                    (null) list of tags to include in the archive
     * @var string|array $public_ids              (null) list of public_ids to include in the archive
     * @var string|array $prefixes                (null) Optional list of prefixes of public IDs (e.g., folders).
     * @var string|array $transformations         Optional list of transformations. The derived images of the given
     *                                     transformations are included in the archive. Using the string
     *                                     representation of multiple chained transformations as we use for the
     *                                     'eager' upload parameter.
     * @var string       $mode                    (create) return the generated archive file or store it as a raw
     *                                     resource and return a JSON with URLs for accessing the archive. Possible
     *                                     values: download, create
     * @var string       $target_format           (zip)
     * @var string       $target_public_id        Optional public ID of the generated raw resource.
     *                                     Relevant only for the create mode. If not specified, a random public ID is
     *                                     generated.
     * @var bool         $flatten_folders         (false) If true, flatten public IDs with folders to be in the root of
     *                                     the archive. Add numeric counter to the file name in case of a name
     *                                     conflict.
     * @var bool         $flatten_transformations (false) If true, and multiple transformations are given,
     *                                     flatten the folder structure of derived images and store the
     *                                     transformation details on the file name instead.
     * @var bool         $use_original_filename   Use the original file name of included images (if available) instead
     *                                     of the public ID.
     * @var bool         $async                   (false) If true, return immediately and perform the archive creation
     *                                     in the background. Relevant only for the create mode.
     * @var string       $notification_url        Optional URL to send an HTTP post request (webhook) when the archive
     *                                     creation is completed.
     * @var string|array $target_tags             Optional array. Allows assigning one or more tag to the
     *                                     generated archive file (for later housekeeping via the admin API).
     * @var string       $keep_derived            (false) keep the derived images used for generating the archive.
     *
     * @return string The resulting archive URL.
     */
    public function downloadArchiveUrl($options = [])
    {
        $options['mode'] = 'download';
        $params          = self::buildArchiveParams($options);

        ApiUtils::signRequest($params, $this->getAccount());

        $assetType = ArrayUtils::get($options, 'resource_type', AssetType::IMAGE);

        return $this->getUploadUrl($assetType, UploadEndPoint::GENERATE_ARCHIVE, $params);
    }

    /**
     * Returns a URL that when invokes creates a zip archive and returns it.
     *
     * @param array $options Additional options. See ArchiveTrait::downloadArchiveUrl.
     *
     * @return string The resulting archive URL
     *
     * @see ArchiveTrait::downloadArchiveUrl
     */
    public function downloadZipUrl($options = [])
    {
        $options['target_format'] = 'zip';

        return $this->downloadArchiveUrl($options);
    }
}
