<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Trait AudioQualifierTrait
 *
 * @api
 */
trait AudioQualifierTrait
{
    /**
     * Sets the audio codec or removes the audio channel.
     *
     * @param string $audioCodec The audio codec or "none".  Use the constants defined in the AudioCodec class.
     *
     * @return AudioCodec
     *
     * @see \Cloudinary\Transformation\AudioCodec
     */
    public static function audioCodec($audioCodec)
    {
        return new AudioCodec($audioCodec);
    }

    /**
     * Sets the audio sample frequency.
     *
     * @param string $audioFrequency The audio frequency.  Use the constants defined in the AudioFrequency class.
     *
     * @return AudioFrequency
     *
     * @see \Cloudinary\Transformation\AudioFrequency
     */
    public static function audioFrequency($audioFrequency)
    {
        return new AudioFrequency($audioFrequency);
    }
}
