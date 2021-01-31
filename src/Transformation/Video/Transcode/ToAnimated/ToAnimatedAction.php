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
 * Class ToAnimatedAction
 */
class ToAnimatedAction extends BaseAction
{
    use ToAnimatedActionTrait;

    /**
     * ToAnimatedAction constructor.
     *
     * @param AnimatedFormat|string $format
     */
    public function __construct($format = null)
    {
        parent::__construct();

        $this->format($format);
    }
}
