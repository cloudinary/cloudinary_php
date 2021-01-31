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
 * Class Sizes
 *
 * 'sizes' attribute of the img tag
 */
class Sizes
{
    /**
     * @var array $breakpoints The breakpoints.
     */
    protected $breakpoints;

    /**
     * Sizes constructor.
     *
     * @param array $breakpoints The breakpoints.
     */
    public function __construct($breakpoints)
    {
        $this->breakpoints = $breakpoints;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        if (empty($this->breakpoints)) {
            return '';
        }

        $sizesItems = [];
        foreach ($this->breakpoints as $breakpoint) {
            $sizesItems[] = "(max-width: {$breakpoint}px) {$breakpoint}px";
        }

        return implode(", ", $sizesItems);
    }
}
