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
 * Defines a base page action.
 */
class BasePageAction extends BaseAction
{
    const MAIN_QUALIFIER = PageQualifier::class;

    /**
     * Adds values to the main qualifier value.
     *
     * @param mixed ...$values The values to add.
     *
     * @return $this
     *
     * @internal
     */
    public function add(...$values)
    {
        $this->getMainQualifier()->add(...$values);

        return $this;
    }
}
