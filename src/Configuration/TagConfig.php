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

/**
 * Class TagConfig
 *
 * @property string $videoPosterFormat Image format of the video poster.
 * @property string $quotesType        Sets the type of the quotes to use (single or double). Default:
 *           BaseTag::DOUBLE_QUOTES.
 * @property string $contentDelimiter  The delimiter between content items.
 *
 * @api
 */
class TagConfig extends BaseConfigSection
{
    const CONFIG_NAME = 'tag';

    const DEFAULT_VIDEO_POSTER_FORMAT = 'jpg';
    const DEFAULT_QUOTES_TYPE         = BaseTag::DOUBLE_QUOTES;
    const DEFAULT_CONTENT_DELIMITER   = "\n";

    // Supported parameters
    const RESPONSIVE             = 'responsive';
    const RESPONSIVE_CLASS       = 'responsive_class';
    const RESPONSIVE_WIDTH       = 'responsive_width';
    const RESPONSIVE_PLACEHOLDER = 'responsive_placeholder';
    const HI_DPI                 = 'hidpi';
    const CLIENT_HINTS           = 'client_hints';
    const UNSIGNED_UPLOAD        = 'unsigned_upload';
    const VIDEO_POSTER_FORMAT    = 'video_poster_format';
    const QUOTES_TYPE            = 'quotes_type';
    const VOID_CLOSING_SLASH     = 'void_closing_slash';
    const SORT_ATTRIBUTES        = 'sort_attributes';
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
     * Whether to use responsive width.
     *
     * @var bool $responsiveWidth
     */
    public $responsiveWidth;

    /**
     * The value of the 'src' attribute.
     *
     * @var string $responsivePlaceholder
     */
    public $responsivePlaceholder;

    /**
     * Whether to use hi dpi.
     *
     * @var bool $hiDpi
     */
    public $hiDpi;

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
     * The delimiter between content items.
     *
     * @var string $contentDelimiter
     */
    protected $contentDelimiter;
}
