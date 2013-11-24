<?php

if (!defined('CLOUDINARY_PATH')) {
    define('CLOUDINARY_PATH', realpath(dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'src') . DS);
}

$config = array();

require_once CLOUDINARY_PATH . "Cloudinary.php";
require_once CLOUDINARY_PATH . "Uploader.php";
require_once CLOUDINARY_PATH . "CloudinaryField.php";
