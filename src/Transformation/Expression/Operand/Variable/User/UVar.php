<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Expression;

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Variable\Variable;

/**
 * Class UVar
 *
 * Represents a user variable expression component.
 */
class UVar extends Expression
{
    /**
     * UVar constructor.
     *
     * @param string $userVariableName The user variable name.
     */
    public function __construct($userVariableName)
    {
        /**
         * @var Variable $userVariableName
         */
        $userVariableName = ClassUtils::verifyInstance($userVariableName, Variable::class);

        parent::__construct($userVariableName->getVariableName());
    }

    /**
     * Named UVar constructor.
     *
     * @param string $userVariableName The user variable name.
     *
     * @return UVar
     */
    public static function uVar($userVariableName)
    {
        return new self($userVariableName);
    }
}
