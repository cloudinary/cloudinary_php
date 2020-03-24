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

use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class IfElse
 */
class IfElse extends BaseParameter
{
    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'if';

    /**
     * IfElse constructor.
     *
     */
    public function __construct()
    {
        parent::__construct('else');
    }
}
