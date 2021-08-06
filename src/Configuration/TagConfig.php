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
    const CONFIG_NAME = 'tag';

    const DEFAULT_VIDEO_POSTER_FORMAT = Format::JPG;
    const DEFAULT_QUOTES_TYPE         = BaseTag::DOUBLE_QUOTES;
    const DEFAULT_CONTENT_DELIMITER   = "\n";
    const DEFAULT_RELATIVE_WIDTH      = 1.0;

    // Supported parameters
    const RESPONSIVE             = 'responsive';
    const RESPONSIVE_CLASS       = 'responsive_class';
    const RESPONSIVE_PLACEHOLDER = 'responsive_placeholder';
    const SIZES                  = 'sizes';
    const RELATIVE_WIDTH         = 'relative_width';
    const HIDPI                  = 'hidpi';
    const CLIENT_HINTS           = 'client_hints';
    const UNSIGNED_UPLOAD        = 'unsigned_upload';
    const VIDEO_POSTER_FORMAT    = 'video_poster_format';
    const QUOTES_TYPE            = 'quotes_type';
    const VOID_CLOSING_SLASH     = 'void_closing_slash';
    const SORT_ATTRIBUTES        = 'sort_attributes';
    const PREPEND_SRC_ATTRIBUTE  = 'prepend_src_attribute';
    const CONTENT_DELIMITER      = 'content_delimiter';

    /**
     * Whether to generate responsive image tags.
     *
     * @var bool $responsive
     */
    public $responsive;

    /**
     * The class of the responsive tag.
     *
     * @var string $responsiveClass
     */
    public $responsiveClass;

    /**
     * The value of the 'src' attribute.
     *
     * @var string $responsivePlaceholder
     */
    public $responsivePlaceholder;

    /**
     * Whether to automatically generate "sizes" attribute if not provided.
     *
     * @var bool|int|string $sizes
     *
     */
    public $sizes;

    /**
     * The percentage of the screen that the image occupies.
     *
     * Used for responsive breakpoints optimization.
     *
     * @var float $relativeWidth Specify a percentage of the screen width (Range: 0.0 to 1.0)
     *
     */
    protected $relativeWidth;

    /**
     * Whether to use hi dpi.
     *
     * @var bool $hidpi
     */
    public $hidpi;

    /**
     * Whether to use client hints.
     *
     * @var bool $clientHints
     */
    public $clientHints;

    /**
     * Whether to perform unsigned upload in the UploadTag.
     *
     * @var bool $unsignedUpload
     *
     * @see UploadTag
     */
    public $unsignedUpload;

    /**
     * Image format of the video poster.
     *
     * @var string $videoPosterFormat
     */
    protected $videoPosterFormat;

    /**
     * Sets the type of the quotes to use (single or double). Default: BaseTag::DOUBLE_QUOTES.
     *
     * @var string $quotesType
     *
     * @see BaseTag::DOUBLE_QUOTES
     */
    protected $quotesType;

    /**
     * Defines whether to add slash to the void tag ending, e.g. "/>" or simply ">".
     *
     * @var bool $voidClosingSlash
     */
    public $voidClosingSlash;

    /**
     * Defines whether to sort attributes by keys alphabetically.
     *
     * @var bool $sortAttributes
     */
    public $sortAttributes;

    /**
     * Defines whether to set "src" attribute first.
     *
     * @var bool $prependSrcAttribute
     */
    public $prependSrcAttribute;

    /**
     * The delimiter between content items.
     *
     * @var string $contentDelimiter
     */
    protected $contentDelimiter;
}
