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

/**
 * Class Media
 *
 * 'media' attribute of the picture source tag
 */
class Media
{
    /**
     * @var int $minWidth The minimum width of the screen.
     */
    protected $minWidth;

    /**
     * @var int $maxWidthThe maximum width of the screen.
     */
    protected $maxWidth;

    /**
     * Media constructor.
     *
     * @param int $minWidth The minimum width of the screen.
     * @param int $maxWidth The maximum width of the screen.
     */
    public function __construct($minWidth = null, $maxWidth = null)
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $mediaQueryConditions = [];

        if (! empty($this->minWidth)) {
            $mediaQueryConditions[] = "(min-width: {$this->minWidth}px)";
        }

        if (! empty($this->maxWidth)) {
            $mediaQueryConditions[] = "(max-width: {$this->maxWidth}px)";
        }

        return implode(' and ', $mediaQueryConditions);
    }
}
