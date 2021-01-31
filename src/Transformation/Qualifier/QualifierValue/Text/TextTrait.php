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

/**
 * Trait TextTrait
 */
trait TextTrait
{
    /**
     * Sets the text.
     *
     * @param string $text The text.
     *
     * @return static
     */
    public function text($text)
    {
        return $this->setText($text);
    }

    /**
     * @internal
     *
     * @param $text
     *
     * @return static
     */
    public function setText($text)
    {
        return $this->setSimpleValue('text', $text);
    }
}
