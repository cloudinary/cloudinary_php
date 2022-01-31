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

/**
 * Class Sizes
 *
 * 'sizes' attribute of the img/source tag
 */
class Sizes
{
    /**
     * @var Configuration $configuration The Configuration instance.
     */
    protected $configuration;

    /**
     * Sizes constructor.
     *
     * @param Configuration $configuration The Configuration instance..
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return (int)($this->configuration->tag->relativeWidth * 100) . 'vw';
    }
}
