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
     * @param string|Image  $image         The public ID or Image instance.
     * @param array         $sources       The sources definitions.
     * @param Configuration $configuration The configuration instance.
     */
    public function __construct($image, $sources, $configuration = null)
    {
        parent::__construct();

        $this->image($image, $configuration);

        $this->setSources($sources);
    }

    /**
     * Sets the image of the picture.
     *
     * @param mixed         $image         The public ID or Image asset.
     * @param Configuration $configuration The configuration instance.
     *
     * @return static
     */
    public function image($image, $configuration = null)
    {
        $this->imageTag = new ImageTag(new Image($image, $configuration), $configuration);

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
                $sourceTag = new PictureSourceTag($this->imageTag->image);
                $sourceTag->media(ArrayUtils::get($source, 'min_width'), ArrayUtils::get($source, 'max_width'));
                $sourceTag->additionalTransformation = ArrayUtils::get($source, 'transformation');

                $source = $sourceTag;
            }

            $this->sources [] = $source;
        }

        return $this;
    }

    /**
     * Serializes the tag content.
     *
     * @param array $additionalContent The additional content.
     *
     * @return string
     */
    public function serializeContent($additionalContent = [])
    {
        return parent::serializeContent(
            ArrayUtils::mergeNonEmpty(
                $this->sources,
                [$this->imageTag],
                $additionalContent
            )
        );
    }
}
