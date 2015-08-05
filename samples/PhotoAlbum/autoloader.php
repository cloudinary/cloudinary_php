<?php

if (!is_file(__DIR__ . '/../../vendor/autoload.php') || !is_readable(__DIR__ . '/../../vendor/autoload.php')) {
    throw new Exception('Unable to load classes. Please run "php composer.phar dump-autoload --optimize" first.');
}

include __DIR__.'/../../vendor/autoload.php';
