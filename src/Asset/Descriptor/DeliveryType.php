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
    const KEY = 'type';

    /**
     * Uploaded public asset.
     *
     * @const string UPLOAD
     */
    const UPLOAD = 'upload';

    /**
     * Private asset.
     *
     * @const string PRIVATE_DELIVERY
     */
    const PRIVATE_DELIVERY = 'private';

    /**
     * Public asset.
     *
     * @const string PUBLIC_DELIVERY
     */
    const PUBLIC_DELIVERY = 'public';

    /**
     * Authenticated asset.
     *
     * @const string AUTHENTICATED
     */
    const AUTHENTICATED = 'authenticated';
    /**
     * Fetch remote asset from URL(ftp/http[s]/s3/gs).
     *
     * @const string FETCH
     */
    const FETCH           = 'fetch';
    const SPRITE          = 'sprite';
    const TEXT            = 'text';
    const MULTI           = 'multi';
    const FACEBOOK        = 'facebook';
    const TWITTER         = 'twitter';
    const TWITTER_NAME    = 'twitter_name';
    const GRAVATAR        = 'gravatar';
    const YOUTUBE         = 'youtube';
    const HULU            = 'hulu';
    const VIMEO           = 'vimeo';
    const ANIMOTO         = 'animoto';
    const WORLDSTARHIPHOP = 'worldstarhiphop';
    const DAILYMOTION     = 'dailymotion';
}
