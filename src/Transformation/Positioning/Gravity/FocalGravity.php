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
 * Defines the area to keep when automatically resizing an image.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#adjusting_the_automatic_gravity_focal_preference
 * target="_blank">Focal gravity</a>
 *
 * @api
 */
class FocalGravity extends GravityParam
{
    use FocalGravityBuilderTrait;

    const AUTO               = 'auto';
    const ADVANCED_FACE      = 'adv_face';
    const ADVANCED_FACES     = 'adv_faces';
    const ADVANCED_EYES      = 'adv_eyes';
    const BODY               = 'body';
    const FACE               = 'face';
    const FACES              = 'faces';
    const NO_FACES           = 'no_faces';
    const CUSTOM             = 'no_faces';
    const CUSTOM_NO_OVERRIDE = 'custom_no_override';
    const OCR_TEXT           = 'ocr_text';
    const NONE               = 'none';

    //TODO: handle aggressive auto gravity

    /**
     * FocalGravity constructor.
     *
     * @param       $focalGravity
     * @param array $fallBacks
     */
    public function __construct($focalGravity, ...$fallBacks)
    {
        parent::__construct();

        $this->setGravity($focalGravity, ...$fallBacks);
    }

    /**
     * Sets the gravity.
     *
     * @param FocalGravity|string $focalGravity The gravity.
     * @param array               $fallBacks    Fallback gravities.
     *
     * @return FocalGravity
     *
     * @internal
     */
    protected function setGravity($focalGravity, ...$fallBacks)
    {
        $this->setParamValue($focalGravity, ...$fallBacks);

        return $this;
    }

    /**
     * Adds fallback gravities.
     *
     * @param array $fallBacks The fallback gravities.
     *
     * @return FocalGravity
     *
     * @internal
     */
    protected function addFallBacks(...$fallBacks)
    {
        $this->value->addValues(...$fallBacks);

        return $this;
    }
}
