# Cloudinary_PHP Changelog

### 1.0.17 (February 10, 2015)
* Add a changelog
* Add support for 'overwrite' option in upload
* Allow root path for shared CDN

### 1.0.16 (December 22, 2014)
* Support folder listing
* Secure domain sharding
* Don't sign version component
* URL suffix and root path support
* Support tags in upload large
* Make call_api public

### 1.0.15 (November 2, 2014)
* Support api_proxy parameter for setting up a proxy between the PHP client and Cloudinary
* Fixed HHVM compatibility issue

### 1.0.14 (October 15, 2014)
* Remove force SSLv3

### 1.0.13 (September 22, 2014)
* Force SSLv3 when contacting the Cloudinary API
* Support invalidation in bulk deletion req (if enabled in your account)

### 1.0.12 (August 24, 2014)
* Support custom_coordinates is upload and update
* Support coordinates in resource details
* Support return_delete_token parameter in upload and cl_image_upload_tag
* Correctly escape parentheses

### 1.0.11 (July 7, 2014)
* Support for auto dpr, auto width and responsive width
* Support for background_removal in upload and update

### 1.0.10 (April 29, 2014)
* Remove closing PHP tags
* Support upload_presets
* Support unsigned uploads
* Support start_at for resource listing
* Support phash for upload and resource details
* Better error message in case of file not found in uploader for PHP 5.5+

### 1.0.9 (February 26, 2014)
* Admin API update method
* Admin API listing by moderation kind and status
* Support moderation status in admin API listing
* Support moderation flag in upload
* New Upload and update API parameters: moderation, ocr, raw_conversation, categorization, detection, similarity_search and auto_tagging
* Support CLOUDINARY_URL ending with /
* Support for uploading large raw files

### 1.0.8 (January 21, 2014)
* Support overwrite upload parameter
* Support specifying face coordinates in upload API
* Support specifying context (currently alt and caption) in upload API and returning context in API
* Support specifying allowed image formats in upload API
* Support listing resources in admin API by multiple public IDs
* Send User-Agent header with client library version in API request
* Support for signed-URLs to override restricted dynamic URLs
* Move helper methods and preloaded file to separate file and fix Composer autoload
* Minor fixes