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
 * @property string $videoPosterFormat
 * @property string $quotesType
 * @property string $contentDelimiter
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
     * @var bool $responsive Whether to generate responsive image tags.
     */
    public $responsive;
    /**
     * @var string $responsiveClass The class of the responsive tag.
     */
    public $responsiveClass;
    /**
     * @var bool $responsiveWidth Whether to use responsive width.
     */
    public $responsiveWidth;
    /**
     * @var string $responsivePlaceholder The value of the 'src' attribute.
     */
    public $responsivePlaceholder;
    /**
     * @var bool $hiDpi Whether to use hi dpi.
     */
    public $hiDpi;
    /**
     * @var bool $clientHints Whether to use client hints.
     */
    public $clientHints;
    /**
     * @var bool $unsignedUpload Whether to perform unsigned upload in the {@see UploadTag}
     */
    public $unsignedUpload;
    /**
     * @var string $videoPosterFormat Image format of the video poster.
     */
    protected $videoPosterFormat;
    /**
     * @var string $quotesType Sets the type of the quotes to use (single or double). Default: BaseTag::DOUBLE_QUOTES.
     */
    protected $quotesType;
    /**
     * @var bool $voidClosingSlash Defines whether to add slash to the void tag ending, e.g. "/>" or simply ">".
     */
    public $voidClosingSlash;
    /**
     * @var bool $sortAttributes Defines whether to sort attributes by keys alphabetically.
     */
    public $sortAttributes;

    /**
     * @var string $contentDelimiter The delimiter between content items.
     */
    protected $contentDelimiter;

}
