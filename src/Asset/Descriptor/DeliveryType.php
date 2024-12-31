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

/**
 * Class DeliveryType
 *
 * @api
 */
class DeliveryType
{
    public const KEY = 'type';

    /**
     * Uploaded public asset.
     *
     * @const string UPLOAD
     */
    public const UPLOAD = 'upload';

    /**
     * Private asset.
     *
     * @const string PRIVATE_DELIVERY
     */
    public const PRIVATE_DELIVERY = 'private';

    /**
     * Public asset.
     *
     * @const string PUBLIC_DELIVERY
     */
    public const PUBLIC_DELIVERY = 'public';

    /**
     * Authenticated asset.
     *
     * @const string AUTHENTICATED
     */
    public const AUTHENTICATED = 'authenticated';
    /**
     * Fetch remote asset from URL(ftp/http[s]/s3/gs).
     *
     * @const string FETCH
     */
    public const FETCH           = 'fetch';
    public const SPRITE          = 'sprite';
    public const TEXT            = 'text';
    public const MULTI           = 'multi';
    public const FACEBOOK        = 'facebook';
    public const TWITTER         = 'twitter';
    public const TWITTER_NAME    = 'twitter_name';
    public const GRAVATAR        = 'gravatar';
    public const YOUTUBE         = 'youtube';
    public const HULU            = 'hulu';
    public const VIMEO           = 'vimeo';
    public const ANIMOTO         = 'animoto';
    public const WORLDSTARHIPHOP = 'worldstarhiphop';
    public const DAILYMOTION     = 'dailymotion';
}
