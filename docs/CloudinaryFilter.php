<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sami\Parser\Filter;

use Sami\Reflection\ClassReflection;
use Sami\Reflection\MethodReflection;
use Sami\Reflection\PropertyReflection;

/**
 * Class CloudinaryFilter
 */
class CloudinaryFilter extends PublicFilter
{
    const API      = 'api';
    const INTERNAL = 'internal';

    /**
     * Accept only classes that are tagged '@api'.
     *
     * @param ClassReflection $class The class reflection object.
     *
     * @return bool
     */
    public function acceptClass(ClassReflection $class)
    {
        return $class->getTags(self::API);
    }

    /**
     * Accept only public methods that are not tagged '@internal'.
     *
     * @param MethodReflection $method The method reflection object.
     *
     * @return bool
     */
    public function acceptMethod(MethodReflection $method)
    {
        return $method->isPublic() && ! $method->getTags(self::INTERNAL);
    }

    /**
     * Accept only public properties that are not tagged '@internal'.
     *
     * @param PropertyReflection $property The property reflection object.
     *
     * @return bool
     */
    public function acceptProperty(PropertyReflection $property)
    {
        return $property->isPublic() && ! $property->getTags(self::INTERNAL);
    }
}
