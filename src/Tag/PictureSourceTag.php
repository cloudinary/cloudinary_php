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
 * Class PictureSourceTag
 *
 * Generates HTML `source` tag that can be used by the `picture` tag.
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
