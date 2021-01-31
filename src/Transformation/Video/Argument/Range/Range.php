<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument\Range;

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\ComponentInterface;
use UnexpectedValueException;

/**
 * Class Range
 */
class Range implements ComponentInterface
{
    const RANGE_RE = '/^(\d+\.)?\d+[%pP]?\.\.(\d+\.)?\d+[%pP]?$/';

    /**
     * @var int The start offset of the range.
     */
    public $startOffset;

    /**
     * @var int The end offset of the range.
     */
    public $endOffset;

    /**
     * Range constructor.
     *
     * @param null $range
     */
    public function __construct($range = null)
    {
        if ($range === null) {
            return;
        }

        list($this->startOffset, $this->endOffset) = self::splitRange($range);
    }

    /**
     * Internal helper method for splitting the range string.
     *
     * @param string|array $range The range to split.
     *
     * @return array|null
     *
     * @internal
     */
    private static function splitRange($range)
    {
        if (is_array($range) && count($range) === 2) {
            return $range;
        }

        if (is_string($range) && preg_match(self::RANGE_RE, $range) === 1) {
            return explode('..', $range, 2);
        }

        throw new UnexpectedValueException('A valid Range is expected');
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeActionQualifiers($this->startOffset, $this->endOffset);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
