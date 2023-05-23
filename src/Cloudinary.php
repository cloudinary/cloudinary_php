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
    const VERSION = '2.11.0';

    /**
     * Defines the Cloudinary cloud details and other global configuration options.
     *
     * @var Configuration $configuration
     */
    public $configuration;

    /**
     * @var TagBuilder $tagBuilder The TagBuilder object that includes builders for all tags.
     */
    protected $tagBuilder;

    /**
     * Cloudinary constructor.
     *
     * @param Configuration|string|array|null $config The Configuration source.
     */
    public function __construct($config = null)
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
     * @return Image
     */
    public function image($publicId)
    {
        return $this->createWithConfiguration($publicId, Image::class);
    }

    /**
     * Creates a new Video instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the video.
     *
     * @return Video
     */
    public function video($publicId)
    {
        return $this->createWithConfiguration($publicId, Video::class);
    }

    /**
     * Creates a new Raw instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the file.
     *
     * @return File
     */
    public function raw($publicId)
    {
        return $this->createWithConfiguration($publicId, File::class);
    }

    /**
     * Returns an instance of the TagBuilder class that includes builders for all tags.
     *
     * @return TagBuilder
     */
    public function tag()
    {
        return $this->tagBuilder;
    }

    /**
     * Creates a new ImageTag instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the image.
     *
     * @return ImageTag
     */
    public function imageTag($publicId)
    {
        return $this->createWithConfiguration($publicId, ImageTag::class);
    }

    /**
     * Creates a new VideoTag instance using the current configuration instance.
     *
     * @param string|mixed $publicId The public ID of the video.
     * @param array|null   $sources  The tag src definition.
     *
     * @return VideoTag
     */
    public function videoTag($publicId, $sources = null)
    {
        $videoTag = ClassUtils::forceInstance($publicId, VideoTag::class, null, $sources, $this->configuration);
        $videoTag->importConfiguration($this->configuration);

        return $videoTag;
    }

    /**
     * Creates a new AdminApi instance using the current configuration instance.
     *
     * @return AdminApi
     */
    public function adminApi()
    {
        return new AdminApi($this->configuration);
    }

    /**
     * Creates a new UploadApi instance using the current configuration instance.
     *
     * @return UploadApi
     */
    public function uploadApi()
    {
        return new UploadApi($this->configuration);
    }

    /**
     * Creates a new SearchApi instance using the current configuration instance.
     *
     * @return SearchApi
     */
    public function searchApi()
    {
        return new SearchApi($this->configuration);
    }

    /**
     * Creates a new SearchFoldersApi instance using the current configuration instance.
     *
     * @return SearchFoldersApi
     */
    public function searchFoldersApi()
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
     * @return mixed
     *
     * @internal
     */
    protected function createWithConfiguration($publicId, $className, ...$args)
    {
        $instance = ClassUtils::forceInstance($publicId, $className, null, $this->configuration, ...$args);
        // this covers the case when an instance of the asset is provided and the line above is a no op.
        $instance->importConfiguration($this->configuration);

        return $instance;
    }
}
