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
use Cloudinary\Api\Search\SearchApi;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Asset\File;
use Cloudinary\Asset\Image;
use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ImageTag;
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
    const VERSION = '2.0.0-beta7';

    /**
     * Defines the Cloudinary account details and other global configuration options.
     *
     * @var Configuration $configuration
     */
    public $configuration;

    /**
     * Cloudinary constructor.
     *
     * @param Configuration|string|array|null $config The Configuration source.
     */
    public function __construct($config = null)
    {
        $this->configuration = new Configuration($config);
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
        return new Image($publicId, $this->configuration);
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
        return new Video($publicId, $this->configuration);
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
        return new File($publicId, $this->configuration);
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
        return new ImageTag($publicId, $this->configuration);
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
        return new VideoTag($publicId, $sources, $this->configuration);
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
}
