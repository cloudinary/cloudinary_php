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
 * Trait CornersTrait
 *
 * @api
 */
trait CornersTrait
{
    /**
     * Sets top-left corner radius
     *
     * @param int $radius Radius length in pixels
     *
     * @return static
     */
    public function topLeft($radius)
    {
        $this->setSimpleValue('topLeft', $radius);

        return $this;
    }

    /**
     * Sets top-right corner radius.
     *
     * @param int $radius Radius length in pixels
     *
     * @return static
     */
    public function topRight($radius)
    {
        $this->setSimpleValue('topRight', $radius);

        return $this;
    }

    /**
     * Sets bottom-right corner radius.
     *
     * @param int $radius Radius length in pixels
     *
     * @return static
     */
    public function bottomRight($radius)
    {
        $this->setSimpleValue('bottomRight', $radius);

        return $this;
    }

    /**
     * Sets bottom-left corner radius.
     *
     * @param int $radius Radius length in pixels
     *
     * @return static
     */
    public function bottomLeft($radius)
    {
        $this->setSimpleValue('bottomLeft', $radius);

        return $this;
    }

    /**
     * Sets radius for all corners.
     *
     * @param int $radius Radius length in pixels
     *
     * @return static
     */
    public function setRadius($radius)
    {
        $this->topLeft($radius);
        $this->topRight(null);
        $this->bottomRight(null);
        $this->bottomLeft(null);

        return $this;
    }

    /**
     * Sets radius separately for (top-left, bottom-right) and (top-right, bottom-left) corners.
     *
     * @param int $topLeftBottomRight Radius for top-left and bottom-right corners
     * @param int $topRightBottomLeft Radius for top-right and bottom-left corners
     *
     * @return static
     */
    public function setSymmetricRadius($topLeftBottomRight, $topRightBottomLeft)
    {
        $this->topLeft($topLeftBottomRight);
        $this->topRight($topRightBottomLeft);
        $this->bottomRight(null);
        $this->bottomLeft(null);

        return $this;
    }

    /**
     * Sets radius separately for top-left, (top-right, bottom-left) and bottom-right corners.
     *
     * @param int $topLeft            Radius for top-left corner
     * @param int $topRightBottomLeft Radius for top-right and bottom-left corners
     * @param int $bottomRight        Radius for bottom-right corner
     *
     * @return static
     */
    public function setPartiallySymmetricRadius($topLeft, $topRightBottomLeft, $bottomRight)
    {
        $this->topLeft($topLeft);
        $this->topRight($topRightBottomLeft);
        $this->bottomRight($bottomRight);
        $this->bottomLeft(null);

        return $this;
    }
}
