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
use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;

/**
 *
 * Generates an HTML `<picture>` tag containing `<source>` and `<img>` tags.
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
class PictureTag extends BaseTag
{
    use ImageTagDeliveryTypeTrait;

    const NAME = 'picture';

    /**
     * @var ImageTag $imageTag The fallback image tag of the picture tag.
     */
    public $imageTag;

    /**
     * @var array of PictureSourceTag $sources
     */
    public $sources;

    /**
     * PictureTag constructor.
     *
     * @param string|Image  $source        The public ID or Image instance.
     * @param array         $sources       The sources definitions.
     * @param Configuration $configuration The configuration instance.
     */
    public function __construct($source, $sources, $configuration = null)
    {
        parent::__construct($configuration);

        $this->image($source);

        $this->setSources($sources);
    }

    /**
     * Sets the image of the picture.
     *
     * @param mixed         $image         The public ID or Image asset.
     *
     * @return static
     */
    public function image($image)
    {
        $this->imageTag = new ImageTag(new Image($image, $this->config), $this->config);

        return $this;
    }

    /**
     * Sets the tag sources.
     *
     * @param array $sourcesDefinitions The definitions of the sources.
     *
     * @return static
     */
    public function setSources($sourcesDefinitions)
    {
        $this->sources = [];

        foreach ($sourcesDefinitions as $source) {
            if (is_array($source)) {
                $sourceTag = new PictureSourceTag(ArrayUtils::get($source, 'image'), $this->config);
                $sourceTag->media(ArrayUtils::get($source, 'min_width'), ArrayUtils::get($source, 'max_width'));
                $sourceTag->sizes(ArrayUtils::get($source, 'sizes'));

                $source = $sourceTag;
            }

            $this->sources [] = $source;
        }

        return $this;
    }

    /**
     * Serializes the tag content.
     *
     * @param array $additionalContent        The additional content.
     * @param bool  $prependAdditionalContent Whether to prepend additional content (instead of append).
     *
     * @return string
     */
    public function serializeContent($additionalContent = [], $prependAdditionalContent = false)
    {
        return parent::serializeContent(
            ArrayUtils::mergeNonEmpty(
                $this->sources,
                [$this->imageTag],
                $additionalContent
            ),
            $prependAdditionalContent
        );
    }
}
