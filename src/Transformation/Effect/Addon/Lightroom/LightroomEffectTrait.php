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
 * Trait LightroomEffectTrait
 *
 * @api
 */
trait LightroomEffectTrait
{
    /**
     * Lightroom filter contrast.
     *
     * @param int $value
     *
     * @return static
     */
    public function contrast($value)
    {
        return $this->addLightroomFilter(LightroomEffect::CONTRAST, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter saturation.
     *
     * @param int $value
     *
     * @return static
     */
    public function saturation($value)
    {
        return $this->addLightroomFilter(LightroomEffect::SATURATION, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter vignetteAmount.
     *
     * @param int $value
     *
     * @return static
     */
    public function vignetteAmount($value)
    {
        return $this->addLightroomFilter(LightroomEffect::VIGNETTE_AMOUNT, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter vibrance.
     *
     * @param int $value
     *
     * @return static
     */
    public function vibrance($value)
    {
        return $this->addLightroomFilter(LightroomEffect::VIBRANCE, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter highlights.
     *
     * @param int $value
     *
     * @return static
     */
    public function highlights($value)
    {
        return $this->addLightroomFilter(LightroomEffect::HIGHLIGHTS, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter shadows.
     *
     * @param int $value
     *
     * @return static
     */
    public function shadows($value)
    {
        return $this->addLightroomFilter(LightroomEffect::SHADOWS, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter whites.
     *
     * @param int $value
     *
     * @return static
     */
    public function whites($value)
    {
        return $this->addLightroomFilter(LightroomEffect::WHITES, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter blacks.
     *
     * @param int $value
     *
     * @return static
     */
    public function blacks($value)
    {
        return $this->addLightroomFilter(LightroomEffect::BLACKS, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter clarity.
     *
     * @param int $value
     *
     * @return static
     */
    public function clarity($value)
    {
        return $this->addLightroomFilter(LightroomEffect::CLARITY, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter dehaze.
     *
     * @param int $value
     *
     * @return static
     */
    public function dehaze($value)
    {
        return $this->addLightroomFilter(LightroomEffect::DEHAZE, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter texture .
     *
     * @param int $value
     *
     * @return static
     */
    public function texture($value)
    {
        return $this->addLightroomFilter(LightroomEffect::TEXTURE, $value, EffectRange::DEFAULT_RANGE);
    }

    /**
     * Lightroom filter sharpness.
     *
     * @param int $value
     *
     * @return static
     */
    public function sharpness($value)
    {
        return $this->addLightroomFilter(LightroomEffect::SHARPNESS, $value, LightroomEffect::SHARPNESS_RANGE);
    }

    /**
     * Lightroom filter color noise reduction.
     *
     * @param int $value
     *
     * @return static
     */
    public function colorNoiseReduction($value)
    {
        return $this->addLightroomFilter(LightroomEffect::COLOR_NOISE_REDUCTION, $value, EffectRange::PERCENT);
    }

    /**
     * Lightroom filter noise reduction.
     *
     * @param int $value
     *
     * @return static
     */
    public function noiseReduction($value)
    {
        return $this->addLightroomFilter(LightroomEffect::NOISE_REDUCTION, $value, EffectRange::PERCENT);
    }

    /**
     * Lightroom filter sharpen detail.
     *
     * @param int $value
     *
     * @return static
     */

    public function sharpenDetail($value)
    {
        return $this->addLightroomFilter(LightroomEffect::SHARPEN_DETAIL, $value, EffectRange::PERCENT);
    }

    /**
     * Lightroom filter sharpen edge masking.
     *
     * @param int $value
     *
     * @return static
     */
    public function sharpenEdgeMasking($value)
    {
        return $this->addLightroomFilter(
            LightroomEffect::SHARPEN_EDGE_MASKING,
            $value,
            LightroomEffect::SHARPEN_EDGE_MASKING_RANGE
        );
    }

    /**
     * Lightroom filter exposure.
     *
     * @param float $value
     *
     * @return static
     */
    public function exposure($value)
    {
        return $this->addLightroomFilter(LightroomEffect::EXPOSURE, $value, LightroomEffect::EXPOSURE_RANGE);
    }

    /**
     * Lightroom filter sharpen radius.
     *
     * @param float $value
     *
     * @return static
     */
    public function sharpenRadius($value)
    {
        return $this->addLightroomFilter(
            LightroomEffect::SHARPEN_RADIUS,
            $value,
            LightroomEffect::SHARPEN_RADIUS_RANGE
        );
    }

    /**
     * Lightroom filter white balance.
     *
     * @param string $value
     *
     * @return static
     */
    public function whiteBalance($value)
    {
        return $this->addLightroomFilter(LightroomEffect::WHITE_BALANCE, $value);
    }


    /**
     * Generic lightroom filter.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return static
     */
    public function genericFilter($name, $value)
    {
        return $this->addLightroomFilter($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @param $range
     *
     * @return static
     */
    abstract public function addLightroomFilter($name, $value, $range = null);
}
