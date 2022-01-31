<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument\Text;

use Cloudinary\StringUtils;
use Cloudinary\Transformation\QualifierMultiValue;

/**
 * Class Text
 */
class Text extends QualifierMultiValue
{
    const VALUE_DELIMITER = '_';

    use TextTrait;

    /**
     * Text constructor
     *
     * @param null $text
     */
    public function __construct($text = null)
    {
        parent::__construct();

        $this->text($text);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $text = parent::__toString();

        $escaped = StringUtils::smartEscape($text);
        # FIXME: move to stringUtils
        $escaped = str_replace(['%2C', '/'], ['%252C', '%252F'], $escaped);
        # Don't encode interpolation expressions e.g. $(variable)
        preg_match_all('/\$\([a-zA-Z]\w+\)/', $text, $matches);
        foreach ($matches[0] as $match) {
            $escaped_match = StringUtils::smartEscape($match);
            $escaped = str_replace($escaped_match, $match, $escaped);
        }

        return (string)$escaped;
    }
}
