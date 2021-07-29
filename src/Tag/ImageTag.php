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

/**
 * Generates an HTML `<img>` tag with the `src` attribute set to the transformation URL, optional `srcset` and other
 * specified attributes.
 *
 * @api
 */
class ImageTag extends BaseImageTag
{
    const NAME    = 'img';
    const IS_VOID = true;

    const BLANK            = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    const RESPONSIVE_CLASS = 'cld-responsive';
    const HI_DPI_CLASS     = 'cld-hidpi';

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     * @return string
     */
    public function serializeAttributes($attributes = [])
    {
        if (($this->config->tag->responsive || $this->config->tag->hidpi) && ! (bool)$this->config->tag->clientHints) {
            $attributes['data-src'] = $this->image;

            $this->addClass($this->config->tag->responsive ? self::RESPONSIVE_CLASS : self::HI_DPI_CLASS);

            $src = $this->config->tag->responsivePlaceholder;
            if ($src === 'blank') {
                $src = self::BLANK;
            }
        } else {
            $src = $this->image->toUrl($this->additionalTransformation);
        }

        ArrayUtils::addNonEmpty($attributes, 'src', $src);

        if (! empty($this->srcset->getBreakpoints())) { // TODO: improve performance here
            $attributes['srcset'] = $this->srcset;

            if (! array_key_exists('sizes', $this->attributes)) {
                if (is_bool($this->config->tag->sizes)) {
                    if ($this->config->tag->sizes) {
                        $attributes['sizes'] = new Sizes($this->config);
                    }
                } elseif (is_string($this->config->tag->sizes)) {
                    $attributes['sizes'] = $this->config->tag->sizes;
                }
            }
        }

        return parent::serializeAttributes($attributes);
    }
}
