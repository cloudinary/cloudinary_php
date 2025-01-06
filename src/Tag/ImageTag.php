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
 * For more information, see the [PHP SDK guide](https://cloudinary.com/documentation/php_image_manipulation#deliver_and_transform_images).
 *
 * @api
 */
class ImageTag extends BaseImageTag
{
    public const NAME = 'img';
    public const IS_VOID = true;

    public const BLANK               = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    protected const RESPONSIVE_CLASS = 'cld-responsive';
    protected const HI_DPI_CLASS     = 'cld-hidpi';

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     */
    public function serializeAttributes(array $attributes = []): string
    {
        if (($this->config->tag->responsive || $this->config->tag->hidpi) && ! $this->config->tag->clientHints) {
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
