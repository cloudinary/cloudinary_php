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

use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait PadTrait
 *
 * @api
 */
trait PadTrait
{
    use CompassPositionTrait;
    use BackgroundTrait;

    /**
     * Resizes the image to fill the given width and height while retaining the original aspect ratio and with all of
     * the original image visible.
     *
     * If the proportions of the original image do not match the given width and height,
     * padding is added to the image to reach the required size
     *
     * @param int|float|string|null $width      The required width of a transformed asset.
     * @param int|float|null        $height     The required height of a transformed asset.
     * @param string|ColorValue     $background Sets the background color of the image.
     *
     * @return Pad
     */
    public static function pad(
        $width = null,
        $height = null,
        $background = null
    ) {
        return static::createPad(CropMode::PAD, $width, $height, $background);
    }

    /**
     * Same as the Pad::pad mode but only if the original image is larger than the given limit (width and
     * height), in which case the image is scaled down to fill the given width and height while retaining the original
     * aspect ratio and with all of the original image visible.
     *
     * This mode doesn't scale up the image if your requested dimensions are bigger than the original image's.
     *
     * @param int|float|string|null $width      The required width of a transformed asset.
     * @param int|float|null        $height     The required height of a transformed asset.
     * @param string|ColorValue     $background Sets the background color of the image.
     *
     * @return Pad
     *
     * @see \Cloudinary\Transformation\Pad::pad
     */
    public static function limitPad(
        $width = null,
        $height = null,
        $background = null
    ) {
        return static::createPad(CropMode::LIMIT_PAD, $width, $height, $background);
    }

    /**
     * Same as the Pad::pad mode but only if the original image is smaller than the given minimum (width and
     * height), in which case the image is scaled up to fill the given width and height while retaining the original
     * aspect ratio and with all of the original image visible.
     *
     * This mode doesn't scale down the image if your requested dimensions are smaller than the original image's.
     *
     * @param int|float|string|null $width      The required width of a transformed asset.
     * @param int|float|null        $height     The required height of a transformed asset.
     * @param string|ColorValue     $background Sets the background color of the image.
     *
     * @return Pad
     *
     * @see \Cloudinary\Transformation\Pad::pad
     */
    public static function minimumPad(
        $width = null,
        $height = null,
        $background = null
    ) {
        return static::createPad(CropMode::MINIMUM_PAD, $width, $height, $background);
    }

    /**
     * Creates Pad instance.
     *
     * @param mixed ...$args
     *
     * @return Pad
     */
    protected static function createPad(...$args)
    {
        return new Pad(...$args);
    }

    /**
     * Sets pad position.
     *
     * @param BasePosition $position The desired position
     *
     * @return $this
     */
    public function position(BasePosition $position)
    {
        return $this->addQualifier($position);
    }

    /**
     * Internal setter for offset.
     *
     * @param $value
     *
     * @return $this
     *
     * @internal
     */
    public function setOffsetValue($value)
    {
        if (! isset($this->qualifiers[CompassPosition::getName()])) {
            $this->addQualifier(new CompassPosition());
        }

        $this->qualifiers[CompassPosition::getName()]->setOffsetValue($value);

        return $this;
    }
}
