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
 *
 * Generates an HTML `<source>` tag with `media` and `srcset` attributes that can be used with a `<picture>` tag.
 *
 * For example:
 *
 * ```
 * <picture>
 * <source srcset="https://res.cloudinary.com/demo/image/upload/c_scale,w_500/sample.png" media="(max-width: 500px)">
 * <source srcset="https://res.cloudinary.com/demo/image/upload/c_scale,w_1000/sample.png" media="(min-width: 500px)">
 * <img src="https://res.cloudinary.com/demo/image/upload/sample.png">
 * </picture>
 * ```
 *
 * @api
 */
class PictureSourceTag extends BaseImageTag
{
    const NAME = 'source';

    const IS_VOID = true;

    /**
     * @var Media $media The media query attribute.
     */
    protected $media;

    /**
     * Sets the media query $minWidth and $maxWidth.
     *
     * @param int $minWidth The minimum width of the screen.
     * @param int $maxWidth The maximum width of the screen.
     *
     * @return PictureSourceTag
     */
    public function media($minWidth = null, $maxWidth = null)
    {
        $this->media = new Media($minWidth, $maxWidth);

        return $this;
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     * @return string
     */
    public function serializeAttributes($attributes = [])
    {
        if (! empty((string)$this->srcset)) {
            $attributes['srcset'] = $this->srcset;
        } else {
            // `source` tag under `picture` tag uses `srcset` attribute for both `srcset` and `src` urls
            $attributes['srcset'] = $this->image->toUrl($this->additionalTransformation);
        }

        if (! empty((string)$this->media)) {
            $attributes['media'] = $this->media;
        }

        return parent::serializeAttributes($attributes);
    }
}
