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

use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\ImageTransformationTrait;

/**
 * Generates an HTML `<link>` tag to specify the relationship to the CSS file associated with an image sprite.
 *
 * For example:
 *
 *```
 * <link type="text/css" rel="stylesheet" href="https://res.cloudinary.com/demo/image/sprite/my_sprite.css">
 * ```
 *
 * @api
 */
class SpriteTag extends BaseTag
{
    use ImageTransformationTrait;

    const NAME    = 'link';
    const IS_VOID = true;

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected $attributes = [
        'type' => 'text/css',
        'rel'  => 'stylesheet',
        // 'href' is set in constructor
    ];

    /**
     * @var Image $image The sprite image of the tag.
     */
    public $image;

    /**
     * @var ImageTransformation $additionalTransformation Additional transformation to be applied on the tag image.
     */
    public $additionalTransformation;

    /**
     * SpriteTag constructor.
     *
     * @param string              $tag - The sprite is created from all images with this tag.
     * @param Configuration       $configuration
     * @param ImageTransformation $additionalTransformation
     */
    public function __construct($tag, $configuration = null, $additionalTransformation = null)
    {
        parent::__construct($configuration);

        $this->image($tag, $configuration);
        $this->additionalTransformation = $additionalTransformation;
    }

    /**
     * Creates a new sprite tag from the provided public id and array of parameters.
     *
     * @param string $tag The public ID of the asset.
     * @param array  $params The media asset parameters.
     *
     * @return SpriteTag
     */
    public static function fromParams($tag, $params = [])
    {
        $spriteTag = new static($tag, self::fromParamsDefaultConfig());

        $spriteTag->image = Image::fromParams($tag, $params);
        $spriteTag->image->deliveryType(DeliveryType::SPRITE)->extension('css');

        return $spriteTag;
    }

    /**
     * Creates the sprite image.
     *
     * @param mixed         $tag           The tag that indicates which images to use in the sprite.
     * @param Configuration $configuration The configuration instance.
     *
     * @return static
     */
    public function image($tag, $configuration = null)
    {
        $this->image = Image::sprite($tag, $configuration);

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
        $attributes['href'] = $this->image->toUrl($this->additionalTransformation);

        return parent::serializeAttributes($attributes);
    }
}
