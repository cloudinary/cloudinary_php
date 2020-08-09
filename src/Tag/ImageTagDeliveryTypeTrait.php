<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;

/**
 * Trait ImageTagDeliveryTypeTrait
 *
 * @api
 */
trait ImageTagDeliveryTypeTrait
{
    /**
     * Static builder for uploaded asset image tag.
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function upload($publicId, $configuration = null)
    {
        return new static(Image::upload($publicId, $configuration));
    }

    /**
     * Static builder for fetch image tag (from URL).
     *
     * @param string                          $url
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function fetch($url, $configuration = null)
    {
        return new static(Image::fetch($url, $configuration));
    }

    /**
     * Static builder for facebook profile picture tag.
     *
     * @param string                          $facebookId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function facebook($facebookId, $configuration = null)
    {
        return new static(Image::facebook($facebookId, $configuration));
    }

    /**
     * Static builder for gravatar profile picture tag.
     *
     * @param string                          $email
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function gravatar($email, $configuration = null)
    {
        return new static(Image::gravatar($email, $configuration));
    }

    /**
     * Static builder for twitter profile picture tag.
     *
     * @param string                          $userId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function twitter($userId, $configuration = null)
    {
        return new static(Image::twitter($userId, $configuration));
    }

    /**
     * Static builder for twitter profile picture by name.
     *
     * @param string                          $username
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function twitterName($username, $configuration = null)
    {
        return new static(Image::twitterName($username, $configuration));
    }

    /**
     * Static builder for the thumbnail of the YouTube video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function youTube($videoId, $configuration = null)
    {
        return new static(Image::youTube($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the YouTube video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function hulu($videoId, $configuration = null)
    {
        return new static(Image::youTube($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the Vimeo video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function vimeo($videoId, $configuration = null)
    {
        return new static(Image::vimeo($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the animoto video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function animoto($videoId, $configuration = null)
    {
        return new static(Image::animoto($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the World Star Hip Hop video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function worldStarHipHop($videoId, $configuration = null)
    {
        return new static(Image::worldStarHipHop($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the DailyMotion video.
     *
     * @param string                          $videoId
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function dailyMotion($videoId, $configuration = null)
    {
        return new static(Image::dailyMotion($videoId, $configuration));
    }

    /**
     * Static builder for sprite tag.
     *
     * @param string                          $tag
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function sprite($tag, $configuration = null)
    {
        return new static(Image::sprite($tag, $configuration));
    }
}
