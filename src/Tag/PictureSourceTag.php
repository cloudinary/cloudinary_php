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

use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;

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
    public const NAME = 'source';

    public const IS_VOID = true;

    /**
     * @var Media $media The media query attribute.
     */
    protected Media $media;

    /**
     * @var mixed $sizes The sizes attribute.
     */
    protected mixed $sizes;

    /**
     * PictureSourceTag constructor.
     *
     * @param string|Image                    $source        The Public ID or Image instance
     * @param int|null                        $minWidth      The minimum width of the screen.
     * @param int|null                        $maxWidth      The maximum width of the screen.
     * @param string|null                     $sizes         The sizes attribute value.
     * @param array|string|Configuration|null $configuration The Configuration source.
     */
    public function __construct(
        string|Image $source,
        ?int $minWidth = null,
        ?int $maxWidth = null,
        ?string $sizes = null,
        Configuration|array|string|null $configuration = null
    ) {
        parent::__construct($source, $configuration);

        $this->sizes($sizes);
        $this->media($minWidth, $maxWidth);
    }

    /**
     * Sets the media query $minWidth and $maxWidth.
     *
     * @param int|null $minWidth The minimum width of the screen.
     * @param int|null $maxWidth The maximum width of the screen.
     *
     */
    public function media(?int $minWidth = null, ?int $maxWidth = null): static
    {
        $this->media = new Media($minWidth, $maxWidth, $this->config);

        $this->config->responsiveBreakpoints->minWidth = $minWidth;
        $this->config->responsiveBreakpoints->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Sets the sizes tag attribute.
     *
     * @param string|null $sizes The sizes attribute value.
     */
    public function sizes(?string $sizes = null): static
    {
        if ($sizes && $this->config->responsiveBreakpoints->autoOptimalBreakpoints) {
            throw new \InvalidArgumentException("Invalid input: sizes must not be used with autoOptimalBreakpoints");
        }

        $this->sizes = $sizes;

        return $this;
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     */
    public function serializeAttributes(array $attributes = []): string
    {
        if (! empty($this->srcset->getBreakpoints())) {
            $attributes['srcset'] = $this->srcset;
        } else {
            // `source` tag under `picture` tag uses `srcset` attribute for both `srcset` and `src` urls
            $attributes['srcset'] = $this->image->toUrl($this->additionalTransformation);
        }

        if (! array_key_exists('sizes', $this->attributes)) {
            if ($this->sizes) {
                $attributes['sizes'] = $this->sizes;
            } elseif (is_string($this->config->tag->sizes)) {
                $attributes['sizes'] = $this->config->tag->sizes;
            } elseif ($this->config->tag->isExplicitlySet('relative_width')) {
                $attributes['sizes'] = new Sizes($this->config);
            }
        }

        if (! empty((string)$this->media)) {
            $attributes['media'] = $this->media;
        }


        return parent::serializeAttributes($attributes);
    }
}
