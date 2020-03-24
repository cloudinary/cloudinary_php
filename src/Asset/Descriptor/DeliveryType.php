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
 */
class DeliveryType
{
    const KEY = 'type';

    /**
     * @const string UPLOAD Uploaded public asset
     */
    const UPLOAD = 'upload';
    /**
     * @const string PRIVATE_ Private asset
     */
    const PRIVATE_DELIVERY = 'private';

    /**
     * @const string PUBLIC Public asset
     */
    const PUBLIC_DELIVERY = 'public';
    /**
     * @const string AUTHENTICATED Authenticated asset
     */
    const AUTHENTICATED = 'authenticated';
    /**
     * @const string FETCH  Fetch remote asset from URL(ftp/http[s]/s3/gs)
     */
    const FETCH           = 'fetch';
    const SPRITE          = 'sprite';
    const TEXT            = 'text';
    const MULTI           = 'multi';
    const FACEBOOK        = 'facebook';
    const TWITTER         = 'twitter';
    const TWITTER_NAME    = 'twitter_name';
    const INSTAGRAM_NAME  = 'instagram_name';
    const GRAVATAR        = 'gravatar';
    const YOUTUBE         = 'youtube';
    const HULU            = 'hulu';
    const VIMEO           = 'vimeo';
    const ANIMOTO         = 'animoto';
    const WORLDSTARHIPHOP = 'worldstarhiphop';
    const DAILYMOTION     = 'dailymotion';
}
