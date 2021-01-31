<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument\Range;

use Cloudinary\Transformation\Argument\BaseNamedArgument;

/**
 * Class Duration
 *
 * !!! Is NOT related to Duration "du_" qualifier!
 */
class PreviewDuration extends BaseNamedArgument
{
    /**
     * @var string The name of the argument.
     */
    protected static $name = 'duration';
}
