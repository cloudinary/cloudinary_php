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
 * Trait QualityBuilderTrait
 *
 * @api
 */
trait QualityBuilderTrait
{
    /**
     * Controls the JPEG, WebP, GIF, JPEG XR and JPEG 2000 compression quality.
     *
     * Reducing the quality is a trade-off between visual quality and file size.
     *
     * @param int $level The quality level. 1 is the lowest quality and 100 is the highest.
     *
     * @return static
     */
    public function quality($level)
    {
        $this->setSimpleValue('level', $level);

        return $this;
    }

    /**
     * Adds an optional qualifier to control chroma subsampling
     *
     * Chroma sub-sampling is a method of encoding images by implementing less resolution for chroma information
     * (colors) than for luma information (luminance), taking advantage of the human visual system's lower acuity for
     * color differences than for luminance
     *
     * @param string $chromaSubSampling Chroma sub-sampling value
     *
     * @return static
     */
    public function chromaSubSampling($chromaSubSampling)
    {
        //TODO: Check valid value
        $this->setSimpleValue('chroma_sub_sampling', $chromaSubSampling);

        return $this;
    }

    /**
     * Controls the final quality by setting a maximum quantization percentage
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#control_the_quality_of_webm_transcoding
     *
     * @param int $quantization The quantization level. Is a % (1-100) setting.
     *
     * @return static
     */
    public function quantization($quantization)
    {
        //TODO: Check valid value
        $this->setSimpleNamedValue('qmax', $quantization);

        return $this;
    }
}
