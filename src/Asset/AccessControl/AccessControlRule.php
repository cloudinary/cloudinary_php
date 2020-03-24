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
 */
class AccessControlRule implements JsonSerializable
{
    /**
     * @var string $accessType The type of the access. Use the constants defined in the AccessType class.
     *
     * @see AccessType for available types.
     */
    protected $accessType;
    /**
     * @var DateTime $start The start time.
     */
    protected $start;
    /**
     * @var DateTime $end The end time.
     */
    protected $end;

    /**
     * AccessControlRule constructor.
     *
     * @param      $accessType
     * @param null $start
     * @param null $end
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
    public function jsonSerialize()
    {
        return ArrayUtils::safeFilter([
            'access_type' => $this->accessType,
            'start'       => Utils::formatDate($this->start),
            'end'         => Utils::formatDate($this->end),
        ]);
    }
}
