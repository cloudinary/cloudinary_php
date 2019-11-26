<?php

namespace Cloudinary;

use DateTime;

/**
 * Class Utils
 *
 * @package Cloudinary
 */
class Utils
{
    /**
     * Formats DateTime in ISO8601 format (date only)
     *
     * @param DateTime $date
     *
     * @return string
     */
    public static function toISO8601DateOnly(DateTime $date)
    {
        return $date->format('Y-m-d');
    }
}
