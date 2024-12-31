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
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function upload(string $publicId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::upload($publicId, $configuration));
    }

    /**
     * Static builder for fetch image tag (from URL).
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function fetch(string $url, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::fetch($url, $configuration));
    }

    /**
     * Static builder for facebook profile picture tag.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function facebook(string $facebookId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::facebook($facebookId, $configuration));
    }

    /**
     * Static builder for gravatar profile picture tag.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function gravatar(string $email, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::gravatar($email, $configuration));
    }

    /**
     * Static builder for twitter profile picture tag.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function twitter(string $userId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::twitter($userId, $configuration));
    }

    /**
     * Static builder for twitter profile picture by name.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function twitterName(string $username, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::twitterName($username, $configuration));
    }

    /**
     * Static builder for the thumbnail of the YouTube video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function youTube(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::youTube($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the YouTube video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function hulu(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::hulu($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the Vimeo video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function vimeo(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::vimeo($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the animoto video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function animoto(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::animoto($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the World Star Hip Hop video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function worldStarHipHop(
        string $videoId,
        Configuration|array|string|null $configuration = null
    ): static {
        return new static(Image::worldStarHipHop($videoId, $configuration));
    }

    /**
     * Static builder for the thumbnail of the DailyMotion video.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function dailyMotion(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::dailyMotion($videoId, $configuration));
    }

    /**
     * Static builder for sprite tag.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function sprite(string $tag, Configuration|array|string|null $configuration = null): static
    {
        return new static(Image::sprite($tag, $configuration));
    }
}
