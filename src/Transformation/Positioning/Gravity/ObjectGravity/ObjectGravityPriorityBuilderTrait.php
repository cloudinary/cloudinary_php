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
 * Trait ObjectGravityPriorityBuilderTrait
 *
 * @property QualifierMultiValue $value
 *
 * @api
 */
trait ObjectGravityPriorityBuilderTrait
{
    /**
     * Gives priority to objects that are in focus, with the specified weighting.
     *
     * @param int|null $weight The priority weighting.
     *
     * @return $this
     */
    public function focus($weight = null)
    {
        return $this->priority(ObjectGravity::FOCUS, $weight);
    }

    /**
     * Gives priority to objects closer to the center of the asset, with the specified weighting.
     *
     * @param int|null $weight The priority weighting.
     *
     * @return $this
     */
    public function center($weight = null)
    {
        return $this->priority(ObjectGravity::CENTER, $weight);
    }

    /**
     * Gives priority to larger objects, with the specified weighting.
     *
     * @param int|null $weight The priority weighting.
     *
     * @return $this
     */
    public function large($weight = null)
    {
        return $this->priority(ObjectGravity::LARGE, $weight);
    }

    /**
     * Sets any priority to the specified weighting.
     *
     * @param string $priorityName The name of the priority.
     * @param int    $weight       The priority weighting.
     *
     * @return $this
     */
    public function priority($priorityName = null, $weight = null)
    {
        $this->value->setSimpleNamedValue($priorityName, $weight);

        return $this;
    }
}
