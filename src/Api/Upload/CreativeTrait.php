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
use GuzzleHttp\Promise\PromiseInterface;

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
     * Creates a sprite from all images that have been assigned a specified tag.
     *
     * The process produces two files:
     * * A single image file containing all the images with the specified tag (PNG by default).
     * * A CSS file that includes the style class names and the location of the individual images in the sprite.
     *
     * This is an asynchronous function.
     *
     * @param string $tag     The tag that indicates which images to include in the sprite.
     * @param array  $options The optional parameters. See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#sprite_method
     */
    public function generateSpriteAsync($tag, $options = [])
    {
        $options['transformation'] = ApiUtils::serializeAssetTransformations($options);

        $params        = ArrayUtils::whitelist($options, ['async', 'notification_url', 'transformation']);
        $params['tag'] = $tag;

        return $this->callUploadApiAsync(UploadEndPoint::SPRITE, $params, $options);
    }

    /**
     * Creates a sprite from all images that have been assigned a specified tag.
     *
     * The process produces two files:
     * * A single image file containing all the images with the specified tag (PNG by default).
     * * A CSS file that includes the style class names and the location of the individual images in the sprite.
     *
     * @param string $tag     The tag that indicates which images to include in the sprite.
     * @param array  $options The optional parameters. See the upload API documentation.
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
     * Creates a single animated image, video or PDF from all image assets that have been
     * assigned a specified tag.
     *
     * This is an asynchronous function.
     *
     * @param string $tag     The tag that indicates which images to include in the animated image, video or PDF.
     * @param array  $options The optional parameters. See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#multi_method
     */
    public function multiAsync($tag, $options = [])
    {
        $options['transformation'] = ApiUtils::serializeAssetTransformations($options);

        $params        = ArrayUtils::whitelist($options, ['format', 'async', 'notification_url', 'transformation']);
        $params['tag'] = $tag;

        return $this->callUploadApiAsync(UploadEndPoint::MULTI, $params, $options);
    }

    /**
     * Creates a single animated image, video or PDF from all image assets that have been
     * assigned a specified tag.
     *
     * @param string $tag     The tag that indicates which images to include in the animated image, video or PDF.
     * @param array  $options The optional parameters. See the upload API documentation.
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
}
