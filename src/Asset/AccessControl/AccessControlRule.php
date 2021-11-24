<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset\AccessControl;

use Cloudinary\ArrayUtils;
use Cloudinary\Utils;
use DateTime;
use JsonSerializable;

/**
 * Class AccessControlRule
 *
 * @api
 */
class AccessControlRule implements JsonSerializable
{
    /**
     * The type of the access. Use the constants defined in the AccessType class.
     *
     * @var string $accessType
     *
     * @see AccessType for available types.
     */
    protected $accessType;

    /**
     * The start time.
     *
     * @var DateTime $start
     */
    protected $start;

    /**
     * The end time.
     *
     * @var DateTime $end
     */
    protected $end;

    /**
     * AccessControlRule constructor.
     *
     * @param string   $accessType The type of the access. Use the constants defined in the AccessType class.
     * @param DateTime $start      The start time.
     * @param DateTime $end        The end time.
     *
     * @see AccessType for available types.
     */
    public function __construct($accessType, $start = null, $end = null)
    {
        $this->accessType = $accessType;
        $this->start      = $start;
        $this->end        = $end;
    }


    /**
     * Serializes to JSON
     *
     * @return array|mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return ArrayUtils::safeFilter(
            [
                'access_type' => $this->accessType,
                'start'       => Utils::formatDate($this->start),
                'end'         => Utils::formatDate($this->end),
            ]
        );
    }
}
