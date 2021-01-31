<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\StringUtils;

/**
 * Class TagUtils
 *
 * @package Cloudinary\Tag
 */
class TagUtils
{
    /**
     * @param array         $tagAttributes
     * @param array         $params
     * @param Configuration $configuration
     */
    public static function handleSpecialAttributes(&$tagAttributes, &$params, $configuration)
    {
        $hasLayer                 = array_key_exists('overlay', $params) || array_key_exists('underlay', $params);
        $hasAngle                 = array_key_exists('angle', $params) && ! empty($params['angle']);
        $cropMode                 = ArrayUtils::get($params, 'crop');
        $responsiveWidth          = ArrayUtils::get($params, 'responsive_width', $configuration->url->responsiveWidth);
        $useResponsiveBreakpoints = array_key_exists('responsive_breakpoints', $params);

        $noHtmlSizes = $hasLayer || $hasAngle || $cropMode === 'fit' || $cropMode === 'limit' || $responsiveWidth
                       || $useResponsiveBreakpoints;

        ArrayUtils::addNonEmpty($params, 'width', ArrayUtils::pop($params, 'html_width'));
        ArrayUtils::addNonEmpty($params, 'height', ArrayUtils::pop($params, 'html_height'));

        $width = ArrayUtils::get($params, 'width');
        if (! (strlen($width) == 0 || ($width && (StringUtils::startsWith($width, 'auto')
                                                  || (float)$width < 1 || $noHtmlSizes)))) {
            ArrayUtils::addNonEmptyFromOther($tagAttributes, 'width', $params);
        }

        $height = ArrayUtils::get($params, 'height');
        if (! (strlen($height) == 0 || (float)$height < 1 || $noHtmlSizes)) {
            ArrayUtils::addNonEmptyFromOther($tagAttributes, 'height', $params);
        }
    }
}
