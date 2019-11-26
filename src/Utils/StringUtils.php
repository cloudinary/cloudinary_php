<?php

namespace Cloudinary;

/**
 * Class StringUtils
 *
 * @package Cloudinary
 */
class StringUtils
{
    public static function camel_case_to_snake_case($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
