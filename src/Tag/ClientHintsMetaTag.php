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

/**
 * Generates an HTML `<meta>` tag to indicate support for Client Hints:
 *
 * ```
 * <meta http-equiv="Accept-CH" content="DPR, Viewport-Width, Width">
 * ```
 *
 * @api
 */
class ClientHintsMetaTag extends BaseTag
{
    /**
     * @var string NAME Mandatory. The name of the tag.
     */
    const NAME = 'meta';

    /**
     * @var bool IS_VOID Indicates whether the tag is a void (self-closed, without body) tag.
     */
    const IS_VOID = true;

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected $attributes = [
        'http-equiv' => 'Accept-CH',
        'content'    => 'DPR, Viewport-Width, Width',
    ];
}
