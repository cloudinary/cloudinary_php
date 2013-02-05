Cloudinary PHP samples
======================

Included in this folder are two sample projects intended to demonstrate the flow of the Cloudinary (PHP) usage.


## Installation
The cloudinary\_php package is ready to be served as-is in your Apache server. (Other servers are also supported, but the access restrictions set in .htaccess will probably not work).
As described in cloudinary\_php main README.md file, you have set up your cloudinary credentials either by passing it as an environment variable or calling Cloudinary::config(). Each sample tries to include `settings.php` for configuration data - you can use the included `settings.php.sample` as a basis for such file.

## Basic sample
This sample is a synchronous script that shows the upload process from local file, remote URL, with different transformations and options.

You can access it through http://<yourserver>/<path-to-cloudinary_php>/samples/basic/basic.php

## Photo Album
A simple web application that allows you to uploads photos, maintain a database with references to them, list them with their metadata, and display them using various transformations

You can access it through http://<yourserver>/<path-to-cloudinary_php>/samples/PhotoAlbum/list.php

