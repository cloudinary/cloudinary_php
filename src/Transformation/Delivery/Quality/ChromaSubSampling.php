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
 * Class ChromaSubSampling
 */
class ChromaSubSampling
{
    const CHROMA_444 = '444';
    const CHROMA_422 = '422';
    const CHROMA_421 = '421';
    const CHROMA_411 = '411';
    const CHROMA_420 = '420';
    const CHROMA_410 = '410';
    const CHROMA_311 = '311';

    /**
     * Chroma subsampling 4:4:4.
     *
     * @return string
     */
    public static function chroma444()
    {
        return self::CHROMA_444;
    }

    /**
     * Chroma subsampling 4:2:2.
     *
     * @return string
     */
    public static function chroma422()
    {
        return self::CHROMA_422;
    }

    /**
     * Chroma subsampling 4:2:1.
     *
     * @return string
     */
    public static function chroma421()
    {
        return self::CHROMA_421;
    }

    /**
     * Chroma subsampling 4:1:1.
     *
     * @return string
     */
    public static function chroma411()
    {
        return self::CHROMA_411;
    }

    /**
     * Chroma subsampling 4:2:0.
     *
     * @return string
     */
    public static function chroma420()
    {
        return self::CHROMA_420;
    }

    /**
     * Chroma subsampling 4:1:0.
     *
     * @return string
     */
    public static function chroma410()
    {
        return self::CHROMA_410;
    }

    /**
     * Chroma subsampling 3:1:1.
     *
     * @return string
     */
    public static function chroma311()
    {
        return self::CHROMA_311;
    }
}
