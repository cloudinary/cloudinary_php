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

use Cloudinary\Asset\DeliveryType;

/**
 * Class XmpSource
 */
class XmpSourceValue extends SourceValue
{
    const XMP = 'xmp';

    protected $argumentOrder = ['asset_type', 'delivery_type', 'source'];

    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $this->setSimpleValue('asset_type', self::XMP);
    }

    /**
     * @param bool $set
     *
     * @return XmpSourceValue
     */
    public function authenticated($set = true)
    {
        return $this->setSimpleValue('delivery_type', $set ? DeliveryType::AUTHENTICATED : null);
    }
}
