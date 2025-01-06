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

use Cloudinary\Configuration\Configuration;
use Cloudinary\Log\LoggerTrait;

/**
 * Class Media
 *
 * 'media' attribute of the picture source tag
 */
class Media
{
    use LoggerTrait;

    /**
     * @var int|null $minWidth The minimum width of the screen.
     */
    protected ?int $minWidth;

    /**
     * @var int|null $maxWidthThe maximum width of the screen.
     */
    protected ?int $maxWidth;

    /**
     * Media constructor.
     *
     * @param int|null           $minWidth      The minimum width of the screen.
     * @param int|null           $maxWidth      The maximum width of the screen.
     * @param Configuration|null $configuration The Configuration source.
     */
    public function __construct(?int $minWidth = null, ?int $maxWidth = null, ?Configuration $configuration = null)
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;

        $this->logging = $configuration->logging;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->minWidth === null && $this->maxWidth === null) {
            $message = 'either minWidth or maxWidth is required';
            $this->getLogger()->critical($message);

            return '';
        }

        if (is_int($this->minWidth) && is_int($this->maxWidth) && $this->minWidth > $this->maxWidth) {
            $message = 'minWidth must be less than maxWidth';
            $this->getLogger()->critical($message);

            return '';
        }

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
