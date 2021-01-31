<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Trait PageNamesTrait
 *
 * @api
 */
trait PageNamesTrait
{
    /**
     * Creates a new instance using provided names.
     *
     * @param string ...$names The names.
     *
     * @return static
     */
    public function byNames(...$names)
    {
        $this->add(new LayerName(...$names));

        return $this;
    }
}
