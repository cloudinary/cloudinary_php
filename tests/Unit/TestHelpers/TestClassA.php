<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\TestHelpers;

/**
 * Class TestClassA
 */
class TestClassA
{
    protected $args;

    /**
     * TestClassA constructor.
     *
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return substr(strrchr(static::class, '\\'), 1) . '(' . implode(', ', $this->args) . ')';
    }
}
