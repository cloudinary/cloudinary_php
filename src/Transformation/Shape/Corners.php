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
 * Class Corners
 */
class Corners extends ParameterMultiValue
{
    const MAX_ARGUMENTS = 4;

    use CornersTrait;

    /**
     * @var array $argumentOrder The order of the arguments.
     */
    protected $argumentOrder = ['topLeft', 'topRight', 'bottomRight', 'bottomLeft'];

    /**
     * Corners constructor.
     *
     * @param int|string $topLeft     Top-left corner radius.
     * @param int        $topRight    Top-right corner radius.
     * @param int        $bottomRight Bottom-right corner radius.
     * @param int        $bottomLeft  Bottom-left corner radius.
     */
    public function __construct($topLeft, $topRight = null, $bottomRight = null, $bottomLeft = null)
    {
        $namedValues = [
            'topLeft'     => $topLeft,
            'topRight'    => $topRight,
            'bottomRight' => $bottomRight,
            'bottomLeft'  => $bottomLeft,
        ];
        parent::__construct($namedValues);
    }
}
