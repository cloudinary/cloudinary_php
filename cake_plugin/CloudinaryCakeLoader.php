<?php

App::uses("CakeLog", "Log");

class CloudinaryCakeLoader {
    static function load() {
        self::fixAutoload();
        self::loadPlugin();
        self::configureCloudinary();
    }

    private static function configureCloudinary() {
        try {
            Configure::load('CloudinaryPrivate');
            \Cloudinary::config(Configure::read('cloudinary'));
        } catch (Exception $e) {
            CakeLog::notice("Couldn't find Config/CloudinaryPrivate.php file");
        }
    }

    private static function loadPlugin() {
        CakePlugin::load('CloudinaryCake', array('bootstrap' => true, 'routes' => false,
            'path' => __DIR__ . DS . 'CloudinaryCake' . DS));
    }

    private static function fixAutoload() {
        // Remove and re-prepend CakePHP's autoloader as composer thinks it is the most important.
        // See https://github.com/composer/composer/commit/c80cb76b9b5082ecc3e5b53b1050f76bb27b127b
        spl_autoload_unregister(array('App', 'load'));
        spl_autoload_register(array('App', 'load'), true, true);
    }
}
