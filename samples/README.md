Cloudinary PHP Sample Project
=============================

Included in this folder are two sample projects for demonstrating the common Cloudinary's usage in PHP.


## Installation

The cloudinary\_php package is ready to be used as-is in your Apache server. (other servers are also supported, but the access restrictions set in .htaccess might not work).
As described in cloudinary\_php main README.md file, you have to set up your Cloudinary credentials either by passing it as the `CLOUDINARY_URL` environment variable or calling Cloudinary::config().
Each sample tries to include `settings.php` (`Config/private.php` for PhotoAlubmCake) for configuration data - you can use the included `settings.php.sample` as a basis for such file.

## Basic sample

This sample is a synchronous script that shows the upload process from local file, remote URL, with different transformations and options.

You can access it through http://YOUR\_SERVER/PATH\_TO\_CLOUDINARY\_PHP/samples/basic/basic.php

Another option is available if you are using PHP 5.4 or higher. Go to the `samples/basic` directory and run:

    php -S localhost:8001

Then you can simply browse to:

	http://localhost:8001/basic.php


## Photo Album

A simple web application that allows you to uploads photos, maintain a database with references to them, list them with their metadata, and display them using various cloud-based transformations.

Make sure to first create a MySQL database (e.g., `create database photo_album`). Then edit `settings.php` to have the correct database details. For example:

    R::setup('mysql:host=localhost;dbname=photo_album', 'my_db_user', 'my_db_password');

You can access it through http://YOUR\_SERVER/PATH\_TO\_CLOUDINARY\_PHP/samples/PhotoAlbum/list.php

Another option is available if you are using PHP 5.4 or higher. Go to the `samples/PhotoAlbum` directory and run:

    php -S localhost:8001

Then you can simply browse to:

	http://localhost:8001/list.php

## Photo Album Cake

This sample demonstrate the usage of the Cloudinary CakePHP plugin. It provides very similar functionalities as the basic Photo Album.

This samples requires CakePHP to be installed. You can follow our directions in [the `cake_plugin/CloudinaryCake/README.md` file](https://github.com/cloudinary/cloudinary_php/tree/master/cake_plugin/CloudinaryCake) using either the manual method or Composer for the installation.

When you finish:

* Setup database config (You can use `Config/database.php.default` as reference by copying it into `Config/database.php` and modifying the relevant fields. See the [CakePHP Cookbook](http://book.cakephp.org/2.0/en/index.html) for more information)
* Create the database table (`cake schema create`)

You can now access the sample through http://YOUR\_SERVER/PATH\_TO\_CLOUDINARY\_PHP/samples/PhotoAlubmCake

*If you use Composer - Please note:* You do not need to bake a new project if you want just to try out the sample. If you do bake one, you'll have to remove the `.htaccess` from your app root directory in order to be able to access the sample in `APP_ROOT/Vendor/cloudinary/cloudinary_php/samples/PhotoAlubmCake`.

Another option is available if you are using PHP 5.4 or higher. Go to the `samples` directory and run -

If you're using a Bourne compatibly shell (sh, bash, zsh):

    NO_REWRITE=1 php -S localhost:8001

On Windows command prompt:

    set NO_REWRITE=1
    php -S localhost:8001

Then you can simply browse to:

	http://localhost:8001/PhotoAlbumCake/index.php/

