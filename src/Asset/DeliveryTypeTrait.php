<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use Cloudinary\Configuration\Configuration;

/**
 * Trait DeliveryTypeTrait
 */
trait DeliveryTypeTrait
{
    /**
     * Static builder for uploaded asset (actually a default constructor, put it here as an alias for consistency).
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function upload(string $publicId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::UPLOAD);
    }

    /**
     * Static builder for private asset
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function private_(string $publicId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::PRIVATE_DELIVERY);
    }

    /**
     * Static builder for authenticated asset
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function authenticated(
        string $publicId,
        Configuration|array|string|null $configuration = null
    ): static {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::AUTHENTICATED);
    }

    /**
     * Static builder for fetch asset (from URL)
     *
     * @param string                          $url           The URL of the remote asset.
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function fetch(string $url, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($url, $configuration, DeliveryType::FETCH);
    }

    /**
     * Static builder for facebook profile picture
     *
     * @param string                          $facebookId    Facebook user ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function facebook(string $facebookId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($facebookId, $configuration, DeliveryType::FACEBOOK);
    }

    /**
     * Static builder for gravatar profile picture
     *
     * @param string                          $email         The email of the gravatar user
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function gravatar(string $email, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder(md5(strtolower(trim($email))), $configuration, DeliveryType::GRAVATAR);
    }

    /**
     * Static builder for twitter profile picture by user ID
     *
     * @param string                          $userId        the User ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function twitter(string $userId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($userId, $configuration, DeliveryType::TWITTER);
    }

    /**
     * Static builder for twitter profile picture by username
     *
     * @param string                          $username      The username.
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function twitterName(string $username, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($username, $configuration, DeliveryType::TWITTER_NAME);
    }

    /**
     * Static builder for YouTube video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function youTube(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::YOUTUBE);
    }

    /**
     * Static builder for hulu video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function hulu(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::HULU);
    }

    /**
     * Static builder for vimeo video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function vimeo(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::VIMEO);
    }

    /**
     * Static builder for animoto video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function animoto(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::ANIMOTO);
    }

    /**
     * Static builder for worldStarHipHop video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function worldStarHipHop(
        string $videoId,
        Configuration|array|string|null $configuration = null
    ): static {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::WORLDSTARHIPHOP);
    }

    /**
     * Static builder for dailyMotion video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function dailyMotion(string $videoId, Configuration|array|string|null $configuration = null): static
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::DAILYMOTION);
    }

    /**
     * Static builder for sprite asset.
     *
     * @param string                          $tag           The tag of the assets
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     */
    public static function sprite(string $tag, Configuration|array|string|null $configuration = null): static
    {
        $image = self::deliveryTypeBuilder($tag, $configuration, DeliveryType::SPRITE);

        $image->asset->extension = 'css';

        return $image;
    }

    /**
     * The actual constructor.
     *
     * @param array|string|Configuration|null $configuration Configuration source.
     *
     *
     * @internal
     */
    protected static function deliveryTypeBuilder(
        $source,
        Configuration|array|string|null $configuration,
        $deliveryType
    ): static {
        $asset = new static($source, $configuration);

        $asset->deliveryType($deliveryType);

        return $asset;
    }
}
