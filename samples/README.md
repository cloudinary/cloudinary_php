Cloudinary PHP Sample Project
=============================

Included in this folder are two sample projects for demonstrating the common Cloudinary's usage in PHP.


## Installation

The cloudinary\_php package is ready to be used as-is in your Apache server. (other servers are also supported, but the access restrictions set in .htaccess might not work).
As described in cloudinary\_php main README.md file, you have to set up your Cloudinary credentials either by passing it as the `CLOUDINARY_URL` environment variable or calling Cloudinary::config(). 
Each sample tries to include `settings.php` for configuration data - you can use the included `settings.php.sample` as a basis for such file.

## Basic sample

This sample is a synchronous script that shows the upload process from local file, remote URL, with different transformations and options.

You can access it through http://YOUR_SERVER/PATH_TO_CLOUDINARY_PHP/samples/basic/basic.php

Another option is available if you are using PHP 5.4 or higher. Go to the `samples/basic` directory and run:

    php -S localhost:8001
    
Then you can simply browse to:

	http://localhost:8001/basic.php     


## Photo Album

A simple web application that allows you to uploads photos, maintain a database with references to them, list them with their metadata, and display them using various cloud-based transformations.

Make sure to first create a MySQL database (e.g., `create database photo_album`). Then edit `settings.php` to have the correct database details. For example:

    R::setup('mysql:host=localhost;dbname=photo_album', 'my_db_user', 'my_db_password');

You can access it through http://YOUR_SERVER/PATH_TO_CLOUDINARY_PHP/samples/PhotoAlbum/list.php

Another option is available if you are using PHP 5.4 or higher. Go to the `samples/PhotoAlbum` directory and run:

    php -S localhost:8001
    
Then you can simply browse to:

	http://localhost:8001/list.php     
