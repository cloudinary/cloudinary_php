<?php

if (class_exists('\Cloudinary') && class_exists('\Cloudinary\Uploader') && class_exists('\CloudinaryField')) {
    return 1;
}

$get_cloudinary_path = function() {
    $autodetect = array(
        realpath(implode(DS, array(dirname(__FILE__), '..', '..', '..', 'src'))),
        realpath(implode(DS, array(dirname(__FILE__), '..', 'Lib', 'Cloudinary'))),
    );
    foreach ($autodetect as $path) {
        $path .= DS;
        if (file_exists($path . "Cloudinary.php")) {
            return $path;
        }
    }
    throw new \Exception("Couldn't guess cloudinary_php src path, please define CLOUDINARY_PATH");
};

if (!defined('CLOUDINARY_PATH')) {
    define('CLOUDINARY_PATH', $get_cloudinary_path());
}
require_once CLOUDINARY_PATH . "Cloudinary.php";
require_once CLOUDINARY_PATH . "Uploader.php";
require_once CLOUDINARY_PATH . "CloudinaryField.php";
