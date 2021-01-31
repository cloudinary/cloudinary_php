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
use Cloudinary\Transformation\Format;

/**
 * Class SourceType
 *
 * 'type' attribute of the source tag
 *
 * @internal
 */
class SourceType
{
    const MEDIA_TYPE_VIDEO = 'video';
    const MEDIA_TYPE_AUDIO = 'audio';

    /**
     * @var string $mediaType The media type. Can be self::MEDIA_TYPE_VIDEO or self::MEDIA_TYPE_AUDIO.
     */
    protected $mediaType;

    /**
     * @var string $type The type(format) of the source.
     */
    public $type;

    /**
     * @var array $codecs The codecs.
     */
    public $codecs = [];

    public static $typeOverrides = [Format::OGV => VideoSourceType::OGG];

    /**
     * SourceType constructor.
     *
     * @param string            $mediaType The media type. Can be self::MEDIA_TYPE_VIDEO or self::MEDIA_TYPE_AUDIO.
     * @param string            $type      The type(format) of the source.
     * @param string|array|null $codecs    The codecs.
     */
    public function __construct($mediaType = null, $type = null, $codecs = null)
    {
        $this->mediaType = $mediaType;
        $this->type      = $type;
        $this->codecs    = ArrayUtils::build($codecs);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->type === null) {
            return '';
        }

        $codecsStr = ! empty($this->codecs) ? 'codecs=' . ArrayUtils::implodeFiltered(', ', $this->codecs) : '';

        $typeStr = ArrayUtils::get(self::$typeOverrides, $this->type, $this->type);

        return ArrayUtils::implodeFiltered('; ', ["{$this->mediaType}/{$typeStr}", $codecsStr]);
    }
}
