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
 * Class Cloudinary
 *
 * @api
 */
class Cloudinary
{
    /**
     * The version of the SDK.
     *
     * @var string VERSION
     */
    const VERSION = '2.0.0-beta6';

    /**
     * The configuration instance.
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
     * Creates a new Image with current instance configuration.
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
     * Creates a new Video with current instance configuration.
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
     * Creates a new Raw with current instance configuration.
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
     * Creates a new ImageTag with current instance configuration.
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
     * Creates a new VideoTag with current instance configuration.
     *
     * @param string|mixed $publicId The public ID of the video.
     * @param array|null   $sources  The tag sources definition.
     *
     * @return VideoTag
     */
    public function videoTag($publicId, $sources = null)
    {
        return new VideoTag($publicId, $sources, $this->configuration);
    }

    /**
     * Creates a new AdminApi with current instance configuration.
     *
     * @return AdminApi
     */
    public function adminApi()
    {
        return new AdminApi($this->configuration);
    }

    /**
     * Creates a new UploadApi with current instance configuration.
     *
     * @return UploadApi
     */
    public function uploadApi()
    {
        return new UploadApi($this->configuration);
    }

    /**
     * Creates a new SearchApi with current instance configuration.
     *
     * @return SearchApi
     */
    public function searchApi()
    {
        return new SearchApi($this->configuration);
    }
}
