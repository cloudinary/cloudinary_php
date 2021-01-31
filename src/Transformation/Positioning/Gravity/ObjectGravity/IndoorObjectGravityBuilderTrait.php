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
 * Trait IndoorObjectGravityBuilderTrait
 *
 * @api
 */
trait IndoorObjectGravityBuilderTrait
{
    /**
     * Gravity indoor.
     *
     * @return string
     */
    public static function indoor()
    {
        return ObjectGravity::INDOOR;
    }

    /**
     * Gravity book.
     *
     * @return string
     */
    public static function book()
    {
        return ObjectGravity::BOOK;
    }

    /**
     * Gravity clock.
     *
     * @return string
     */
    public static function clock()
    {
        return ObjectGravity::CLOCK;
    }

    /**
     * Gravity vase.
     *
     * @return string
     */
    public static function vase()
    {
        return ObjectGravity::VASE;
    }

    /**
     * Gravity scissors.
     *
     * @return string
     */
    public static function scissors()
    {
        return ObjectGravity::SCISSORS;
    }

    /**
     * Gravity teddyBear.
     *
     * @return string
     */
    public static function teddyBear()
    {
        return ObjectGravity::TEDDY_BEAR;
    }

    /**
     * Gravity hairDrier.
     *
     * @return string
     */
    public static function hairDrier()
    {
        return ObjectGravity::HAIR_DRIER;
    }

    /**
     * Gravity toothbrush.
     *
     * @return string
     */
    public static function toothbrush()
    {
        return ObjectGravity::TOOTHBRUSH;
    }
}
