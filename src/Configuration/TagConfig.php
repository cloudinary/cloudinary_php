<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration;

use Cloudinary\Tag\BaseTag;
use Cloudinary\Transformation\Format;

/**
 * Defines the global configuration for html tags generated using the Cloudinary PHP SDK.
 *
 * @property string $videoPosterFormat Image format of the video poster.
 * @property string $quotesType        Sets the type of the quotes to use (single or double). Default:
 *           BaseTag::DOUBLE_QUOTES.
 * @property string $contentDelimiter  The delimiter between content items.
 * @property float  $relativeWidth     The percentage of the screen that the image occupies.
 *
 * @api
 */
class TagConfig extends BaseConfigSection
{
    use TagConfigTrait;

    public const CONFIG_NAME = 'tag';

    public const DEFAULT_VIDEO_POSTER_FORMAT = Format::JPG;
    public const DEFAULT_QUOTES_TYPE         = BaseTag::DOUBLE_QUOTES;
    public const DEFAULT_CONTENT_DELIMITER = "\n";
    public const DEFAULT_RELATIVE_WIDTH    = 1.0;

    // Supported parameters
    public const RESPONSIVE = 'responsive';
    public const RESPONSIVE_CLASS = 'responsive_class';
    public const RESPONSIVE_PLACEHOLDER = 'responsive_placeholder';
    public const SIZES                  = 'sizes';
    public const RELATIVE_WIDTH  = 'relative_width';
    public const HIDPI          = 'hidpi';
    public const CLIENT_HINTS = 'client_hints';
    public const UNSIGNED_UPLOAD = 'unsigned_upload';
    public const VIDEO_POSTER_FORMAT = 'video_poster_format';
    public const USE_FETCH_FORMAT    = 'use_fetch_format';
    public const QUOTES_TYPE      = 'quotes_type';
    public const VOID_CLOSING_SLASH = 'void_closing_slash';
    public const SORT_ATTRIBUTES    = 'sort_attributes';
    public const PREPEND_SRC_ATTRIBUTE = 'prepend_src_attribute';
    public const CONTENT_DELIMITER     = 'content_delimiter';

    /**
     * Whether to generate responsive image tags.
     *
     * @var ?bool $responsive
     */
    public ?bool $responsive = null;

    /**
     * The class of the responsive tag.
     *
     * @var ?string $responsiveClass
     */
    public ?string $responsiveClass = null;

    /**
     * The value of the 'src' attribute.
     *
     * @var ?string $responsivePlaceholder
     */
    public ?string $responsivePlaceholder = null;

    /**
     * Whether to automatically generate "sizes" attribute if not provided.
     *
     * @var bool|int|string $sizes
     *
     */
    public string|int|bool|null $sizes = null;

    /**
     * The percentage of the screen that the image occupies.
     *
     * Used for responsive breakpoints optimization.
     *
     * @var ?float $relativeWidth Specify a percentage of the screen width (Range: 0.0 to 1.0)
     *
     */
    protected ?float $relativeWidth = null;

    /**
     * Whether to use hi dpi.
     *
     * @var ?bool $hidpi
     */
    public ?bool $hidpi = null;

    /**
     * Whether to use client hints.
     *
     * @var bool $clientHints
     */
    public ?bool $clientHints = null;

    /**
     * Whether to perform unsigned upload in the UploadTag.
     *
     * @var bool $unsignedUpload
     *
     * @see UploadTag
     */
    public ?bool $unsignedUpload = null;

    /**
     * Image format of the video poster.
     *
     * @var ?string $videoPosterFormat
     */
    protected ?string $videoPosterFormat;

    /**
     * Whether to use fetch format transformation ("f_") instead of file extension.
     *
     * @var ?string $useFetchFormat
     */
    public ?string $useFetchFormat = null;

    /**
     * Sets the type of the quotes to use (single or double). Default: BaseTag::DOUBLE_QUOTES.
     *
     * @var string $quotesType
     *
     * @see BaseTag::DOUBLE_QUOTES
     */
    protected string $quotesType;

    /**
     * Defines whether to add slash to the void tag ending, e.g. "/>" or simply ">".
     *
     * @var ?bool $voidClosingSlash
     */
    public ?bool $voidClosingSlash = null;

    /**
     * Defines whether to sort attributes by keys alphabetically.
     *
     * @var bool $sortAttributes
     */
    public ?bool $sortAttributes = null;

    /**
     * Defines whether to set "src" attribute first.
     *
     * @var ?bool $prependSrcAttribute
     */
    public ?bool $prependSrcAttribute = null;

    /**
     * The delimiter between content items.
     *
     * @var ?string $contentDelimiter
     */
    protected ?string $contentDelimiter = null;

    /**
     * Sets the Tag configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setTagConfig($configKey, $configValue): static
    {
        return $this->setConfig($configKey, $configValue);
    }
}
