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
use Cloudinary\Transformation\Transformation;
use GuzzleHttp\Promise\PromiseInterface;
use InvalidArgumentException;

/**
 * Trait CreativeTrait
 *
 * @property ApiClient $apiClient Defined in UploadApi class
 *
 * @api
 */
trait CreativeTrait
{
    /**
     * Creates a sprite from all images that have been assigned a specified tag or from a provided array of image URLs.
     *
     * The process produces two files:
     * * A single sprite image file containing all the images.
     * * A CSS file that includes the style class names and the location of the individual images in the sprite.
     *
     * This is an asynchronous function.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#sprite_method
     */
    public function generateSpriteAsync($tag, $options = [])
    {
        $params = self::buildSpriteAndMultiParams($tag, $options);

        return $this->callUploadApiJsonAsync(UploadEndPoint::SPRITE, $params, $options);
    }

    /**
     * Creates a sprite from all images that have been assigned a specified tag or from a provided array of image URLs.
     *
     * The process produces two files:
     * * A single sprite image file containing all the images.
     * * A CSS file that includes the style class names and the location of the individual images in the sprite.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#sprite_method
     */
    public function generateSprite($tag, $options = [])
    {
        return $this->generateSpriteAsync($tag, $options)->wait();
    }

    /**
     * Generates an url to create a sprite from all images that have been assigned a specified tag or from a provided
     * array of URLs.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return string
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#sprite_method
     */
    public function downloadGeneratedSprite($tag, $options = [])
    {
        $params = self::buildSpriteAndMultiParams($tag, $options);

        $params['mode'] = self::MODE_DOWNLOAD;

        $params = ApiUtils::finalizeUploadApiParams($params);

        ApiUtils::signRequest($params, $this->getCloud());

        return $this->getUploadUrl(AssetType::IMAGE, UploadEndPoint::SPRITE, $params);
    }

    /**
     * Creates a single animated image, video or PDF from all image assets that have been assigned a specified tag or
     * from a provided array of URLs.
     *
     * This is an asynchronous function.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#multi_method
     */
    public function multiAsync($tag, $options = [])
    {
        $params = self::buildSpriteAndMultiParams($tag, $options);

        return $this->callUploadApiJsonAsync(UploadEndPoint::MULTI, $params, $options);
    }

    /**
     * Creates a single animated image, video or PDF from all image assets that have been assigned a specified tag or
     * from a provided array of URLs.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#multi_method
     */
    public function multi($tag, $options = [])
    {
        return $this->multiAsync($tag, $options)->wait();
    }

    /**
     * Generates an url to create a single animated image, video or PDF from all image assets that have been assigned
     * a specified tag or from a provided array of URLs.
     *
     * @param string|array $tag     A string specifying a tag that indicates which images to include or an array
     *                              which include options and image URLs.
     * @param array        $options The optional parameters. Should be omitted when $tag is an array.
     *
     * @return string
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#sprite_method
     */
    public function downloadMulti($tag, $options = [])
    {
        $params = self::buildSpriteAndMultiParams($tag, $options);

        $params['mode'] = self::MODE_DOWNLOAD;

        $params = ApiUtils::finalizeUploadApiParams($params);

        ApiUtils::signRequest($params, $this->getCloud());

        return $this->getUploadUrl(AssetType::IMAGE, UploadEndPoint::MULTI, $params);
    }

    /**
     * Creates derived images for all of the individual pages in a multi-page file (PDF or animated GIF).
     *
     * Each derived image is stored with the same public ID as the original file, and can be accessed using the page
     * parameter, in order to deliver a specific image.
     *
     * This is an asynchronous function.
     *
     * @param string $publicId The public ID of the multi-page file.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#explode_method
     */
    public function explodeAsync($publicId, $options = [])
    {
        $options['transformation'] = ApiUtils::serializeAssetTransformations($options);

        $params              = ArrayUtils::whitelist(
            $options,
            ['format', 'type', 'notification_url', 'transformation']
        );
        $params['public_id'] = $publicId;

        return $this->callUploadApiAsync(UploadEndPoint::EXPLODE, $params, $options);
    }

    /**
     * Creates derived images for all of the individual pages in a multi-page file (PDF or animated GIF).
     *
     * Each derived image is stored with the same public ID as the original file, and can be accessed using the page
     * parameter, in order to deliver a specific image.
     *
     * @param string $publicId The public ID of the multi-page file.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#explode_method
     */
    public function explode($publicId, $options = [])
    {
        return $this->explodeAsync($publicId, $options)->wait();
    }

    /**
     * Dynamically generates an image from a given textual string.
     *
     * This is an asynchronous function.
     *
     * @param string $text    The text string to generate an image for.
     * @param array  $options The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#text_method
     */
    public function textAsync($text, $options = [])
    {
        $params         = ArrayUtils::whitelist(
            $options,
            [
                'public_id',
                'font_family',
                'font_size',
                'font_color',
                'text_align',
                'font_weight',
                'font_style',
                'background',
                'opacity',
                'text_decoration',
            ]
        );
        $params['text'] = $text;

        return $this->callUploadApiAsync(UploadEndPoint::TEXT, $params, $options);
    }

    /**
     * Dynamically generates an image from a given textual string.
     *
     * @param string $text    The text string to generate an image for.
     * @param array  $options The optional parameters.  See the upload API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#text_method
     */
    public function text($text, $options = [])
    {
        return $this->textAsync($text, $options)->wait();
    }

    /**
     * Build params for multi, downloadMulti, generateSprite, and downloadGeneratedSprite methods.
     *
     * @param string|array $tagOrOptions A string specifying a tag that indicates which images to include or an array
     *                                   which include image URLs and options.
     * @param array        $options      The optional parameters. Should be omitted when $tagOrOptions is an array.
     *
     * @return array
     */
    private static function buildSpriteAndMultiParams($tagOrOptions, $options)
    {
        if (is_array($tagOrOptions)) {
            if (empty($options)) {
                $options = $tagOrOptions;
            } else {
                throw new InvalidArgumentException('First argument must be a tag when additional options are passed');
            }
            $tag = null;
        } else {
            $tag = $tagOrOptions;
        }
        $urls = ArrayUtils::get($options, 'urls');

        if (empty($tag) && empty($urls)) {
            throw new InvalidArgumentException('Either tag or urls are required');
        }

        $transformation = new Transformation($options);
        $transformation->format(ArrayUtils::get($options, 'format'));
        $options['transformation'] = ApiUtils::serializeAssetTransformations($transformation);

        $params = ArrayUtils::whitelist($options, ['format', 'async', 'notification_url', 'transformation']);
        if (isset($urls)) {
            $params['urls'] = $urls;
        } else {
            $params['tag'] = $tag;
        }
        return $params;

     }
    /**
     * Create auto-generated video slideshows.
     *
     * @param array $options The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/video_slideshow_generation
     */
    public function createSlideshowAsync($options = [])
    {
        $params = ArrayUtils::whitelist(
            $options,
            [
                'notification_url',
                'public_id',
                'overwrite',
                'upload_preset',
            ]
        );

        $complexParams = [
            'manifest_transformation' => ApiUtils::serializeAssetTransformations(ArrayUtils::get(
                $options,
                'manifest_transformation'
            )),
            'manifest_json'           => ApiUtils::serializeJson(ArrayUtils::get($options, 'manifest_json')),
            'tags'                    => ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'tags')),
            'transformation'          => ApiUtils::serializeAssetTransformations(ArrayUtils::get(
                $options,
                'transformation'
            )),
        ];

        ArrayUtils::setDefaultValue($options, AssetType::KEY, AssetType::VIDEO);

        return $this->callUploadApiAsync(
            UploadEndPoint::CREATE_SLIDESHOW,
            array_merge($params, $complexParams),
            $options
        );
    }

    /**
     * Create auto-generated video slideshows.
     *
     * @param array $options The optional parameters.  See the upload API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/video_slideshow_generation
     */
    public function createSlideshow($options = [])
    {
        return $this->createSlideshowAsync($options)->wait();
    }
}
