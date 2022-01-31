<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Expression;

/**
 * Trait PredefinedVariableTrait
 *
 * @api
 */
trait PredefinedVariableTrait
{
    /**
     * Predefined variable width.
     *
     * @return static
     */
    public static function width()
    {
        return static::pVar(static::WIDTH);
    }

    /**
     * Predefined variable initialWidth.
     *
     * @return static
     */
    public static function initialWidth()
    {
        return static::pVar(static::INITIAL_WIDTH);
    }

    /**
     * Predefined variable height.
     *
     * @return static
     */
    public static function height()
    {
        return static::pVar(static::HEIGHT);
    }

    /**
     * Predefined variable initialHeight.
     *
     * @return static
     */
    public static function initialHeight()
    {
        return static::pVar(static::INITIAL_HEIGHT);
    }

    /**
     * Predefined variable aspectRatio.
     *
     * @return static
     */
    public static function aspectRatio()
    {
        return static::pVar(static::ASPECT_RATIO);
    }

    /**
     * Predefined variable initialAspectRatio.
     *
     * @return static
     */
    public static function initialAspectRatio()
    {
        return static::pVar(static::INITIAL_ASPECT_RATIO);
    }

    /**
     * Predefined variable trimmedAspectRatio.
     *
     * @return static
     */
    public static function trimmedAspectRatio()
    {
        return static::pVar(static::TRIMMED_ASPECT_RATIO);
    }

    /**
     * Predefined variable pageCount.
     *
     * @return static
     */
    public static function pageCount()
    {
        return static::pVar(static::PAGE_COUNT);
    }

    /**
     * Predefined variable duration.
     *
     * @return static
     */
    public static function duration()
    {
        return static::pVar(static::DURATION);
    }

    /**
     * Predefined variable initialDuration.
     *
     * @return static
     */
    public static function initialDuration()
    {
        return static::pVar(static::INITIAL_DURATION);
    }

    /**
     * Predefined variable faceCount.
     *
     * @return static
     */
    public static function faceCount()
    {
        return static::pVar(static::FACE_COUNT);
    }

    /**
     * Predefined variable illustrationScore.
     *
     * @return static
     */
    public static function illustrationScore()
    {
        return static::pVar(static::ILLUSTRATION_SCORE);
    }

    /**
     * Predefined variable currentPage.
     *
     * @return static
     */
    public static function currentPage()
    {
        return static::pVar(static::CURRENT_PAGE);
    }

    /**
     * Predefined variable pageX.
     *
     * @return static
     */
    public static function pageX()
    {
        return static::pVar(static::PAGE_X);
    }

    /**
     * Predefined variable pageY.
     *
     * @return static
     */
    public static function pageY()
    {
        return static::pVar(static::PAGE_Y);
    }

    /**
     * Predefined variable tags.
     *
     * @return static
     */
    public static function tags()
    {
        return static::pVar(static::TAGS);
    }

    /**
     * Predefined variable context.
     *
     * @return static
     */
    public static function context()
    {
        return static::pVar(static::CONTEXT);
    }
}
