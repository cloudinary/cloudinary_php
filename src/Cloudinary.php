<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Search\SearchFoldersApi;
use Cloudinary\Api\Search\SearchApi;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Asset\File;
use Cloudinary\Asset\Image;
use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Tag\TagBuilder;
use Cloudinary\Tag\VideoTag;

/**
 * Defines the Cloudinary instance.
 *
 * @api
 */
class Cloudinary
{
    /**
     * The current version of the SDK.
     *
     * @var string VERSION
     */
    public const VERSION = '3.1.0';

    /**
     * Defines the Cloudinary cloud details and other global configuration options.
     *
     * @var Configuration $configuration
     */
    public Configuration $configuration;

    /**
     * @var TagBuilder $tagBuilder The TagBuilder object that includes builders for all tags.
     */
    protected TagBuilder $tagBuilder;

    /**
     * Cloudinary constructor.
     *
     * @param array|string|Configuration|null $config The Configuration source.
     */
    public function __construct(Configuration|array|string|null $config = null)
    {
        $this->configuration = new Configuration($config);
        $this->configuration->validate();

        $this->tagBuilder    = new TagBuilder($this->configuration);
    }

    /**
     * Creates a new Image instance using the current configuration instance.
     *
     * @param string $publicId The public ID of the image.
     *
     */
    public function image(string $publicId): Image
    {
        return $this->createWithConfiguration($publicId, Image::class);
    }

    /**
     * Creates a new Video instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the video.
     *
     */
    public function video(mixed $publicId): Video
    {
        return $this->createWithConfiguration($publicId, Video::class);
    }

    /**
     * Creates a new Raw instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the file.
     *
     */
    public function raw(mixed $publicId): File
    {
        return $this->createWithConfiguration($publicId, File::class);
    }

    /**
     * Returns an instance of the TagBuilder class that includes builders for all tags.
     *
     */
    public function tag(): TagBuilder
    {
        return $this->tagBuilder;
    }

    /**
     * Creates a new ImageTag instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the image.
     *
     */
    public function imageTag(mixed $publicId): ImageTag
    {
        return $this->createWithConfiguration($publicId, ImageTag::class);
    }

    /**
     * Creates a new VideoTag instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the video.
     * @param array|null   $sources  The tag src definition.
     *
     */
    public function videoTag(mixed $publicId, ?array $sources = null): VideoTag
    {
        $videoTag = ClassUtils::forceInstance($publicId, VideoTag::class, null, $sources, $this->configuration);
        $videoTag->importConfiguration($this->configuration);

        return $videoTag;
    }

    /**
     * Creates a new AdminApi instance using the current configuration instance.
     *
     */
    public function adminApi(): AdminApi
    {
        return new AdminApi($this->configuration);
    }

    /**
     * Creates a new UploadApi instance using the current configuration instance.
     *
     */
    public function uploadApi(): UploadApi
    {
        return new UploadApi($this->configuration);
    }

    /**
     * Creates a new SearchApi instance using the current configuration instance.
     *
     */
    public function searchApi(): SearchApi
    {
        return new SearchApi($this->configuration);
    }

    /**
     * Creates a new SearchFoldersApi instance using the current configuration instance.
     *
     */
    public function searchFoldersApi(): SearchFoldersApi
    {
        return new SearchFoldersApi($this->configuration);
    }

    /**
     * Creates a new object and imports current instance configuration.
     *
     * @param mixed  $publicId  The public Id or the object.
     * @param string $className The class name of the object to create.
     * @param mixed  ...$args   Additional constructor arguments.
     *
     *
     * @internal
     */
    protected function createWithConfiguration(mixed $publicId, string $className, ...$args): mixed
    {
        $instance = ClassUtils::forceInstance($publicId, $className, null, $this->configuration, ...$args);
        // this covers the case when an instance of the asset is provided and the line above is a no op.
        $instance->importConfiguration($this->configuration);

        return $instance;
    }
}
