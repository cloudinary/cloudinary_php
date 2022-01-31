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
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function upload($publicId, $configuration = null)
    {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::UPLOAD);
    }

    /**
     * Static builder for private asset
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function private_($publicId, $configuration = null)
    {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::PRIVATE_DELIVERY);
    }

    /**
     * Static builder for authenticated asset
     *
     * @param string                          $publicId      The public ID of the asset.
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function authenticated($publicId, $configuration = null)
    {
        return self::deliveryTypeBuilder($publicId, $configuration, DeliveryType::AUTHENTICATED);
    }

    /**
     * Static builder for fetch asset (from URL)
     *
     * @param string                          $url           The URL of the remote asset.
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function fetch($url, $configuration = null)
    {
        return self::deliveryTypeBuilder($url, $configuration, DeliveryType::FETCH);
    }

    /**
     * Static builder for facebook profile picture
     *
     * @param string                          $facebookId    Facebook user ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function facebook($facebookId, $configuration = null)
    {
        return self::deliveryTypeBuilder($facebookId, $configuration, DeliveryType::FACEBOOK);
    }

    /**
     * Static builder for gravatar profile picture
     *
     * @param string                          $email         The email of the gravatar user
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function gravatar($email, $configuration = null)
    {
        return self::deliveryTypeBuilder(md5(strtolower(trim($email))), $configuration, DeliveryType::GRAVATAR);
    }

    /**
     * Static builder for twitter profile picture by user ID
     *
     * @param string                          $userId        the User ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function twitter($userId, $configuration = null)
    {
        return self::deliveryTypeBuilder($userId, $configuration, DeliveryType::TWITTER);
    }

    /**
     * Static builder for twitter profile picture by username
     *
     * @param string                          $username      The username.
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function twitterName($username, $configuration = null)
    {
        return self::deliveryTypeBuilder($username, $configuration, DeliveryType::TWITTER_NAME);
    }

    /**
     * Static builder for YouTube video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function youTube($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::YOUTUBE);
    }

    /**
     * Static builder for hulu video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function hulu($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::HULU);
    }

    /**
     * Static builder for vimeo video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function vimeo($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::VIMEO);
    }

    /**
     * Static builder for animoto video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function animoto($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::ANIMOTO);
    }

    /**
     * Static builder for worldStarHipHop video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function worldStarHipHop($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::WORLDSTARHIPHOP);
    }

    /**
     * Static builder for dailyMotion video thumbnail
     *
     * @param string                          $videoId       The video ID
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function dailyMotion($videoId, $configuration = null)
    {
        return self::deliveryTypeBuilder($videoId, $configuration, DeliveryType::DAILYMOTION);
    }

    /**
     * Static builder for sprite asset.
     *
     * @param string                          $tag           The tag of the assets
     * @param Configuration|string|array|null $configuration Configuration source.
     *
     * @return static
     */
    public static function sprite($tag, $configuration = null)
    {
        $image = self::deliveryTypeBuilder($tag, $configuration, DeliveryType::SPRITE);

        $image->asset->extension = 'css';

        return $image;
    }

    /**
     * The actual constructor.
     *
     * @param                                 $source
     * @param Configuration|string|array|null $configuration Configuration source.
     * @param                                 $deliveryType
     *
     * @return static
     *
     * @internal
     */
    protected static function deliveryTypeBuilder($source, $configuration, $deliveryType)
    {
        $asset = new static($source, $configuration);

        $asset->deliveryType($deliveryType);

        return $asset;
    }
}
