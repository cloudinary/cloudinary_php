Cloudinary PHP SDK v2 Beta
==========

Cloudinary is a cloud service that offers a solution to a web application's entire image management pipeline. 

Easily upload images to the cloud. Automatically perform smart image resizing, cropping and conversion without installing any complex software. Integrate Facebook or Twitter profile image extraction in a snap, in any dimension and style to match your website's graphics requirements. Images are seamlessly delivered through a fast CDN, and much much more. 

Cloudinary offers comprehensive APIs and administration capabilities and is easy to integrate with any web application, existing or new.

Cloudinary provides URL and HTTP based APIs that can be easily integrated with any Web development framework. 

For PHP, Cloudinary provides an extension for simplifying the integration even further.

## Setup ######################################################################

You can install through composer with:

```
composer require cloudinary/cloudinary_php:2.0.0-beta
```

Or download cloudinary_php v2 from [here](https://github.com/cloudinary/cloudinary_php/tree/2.0.0-beta)

*Note: cloudinary_php v2 require PHP 5.6*

## Usage

### Configuration

Each request for building a URL of a remote cloud resource must have the `cloud_name` parameter set. 
Each request to our secure APIs (e.g., image uploads, eager sprite generation) must have the `key` and `secret` parameters set. See [API, URLs and access identifiers](http://cloudinary.com/documentation/api_and_access_identifiers) for more details.

Setting the cloud_name, key and secret parameters can be done either directly in each call to a Cloudinary method, by when initializing the Cloudinary object, or by using the CLOUDINARY_URL environment variable / system property.

The entry point of the library is the Cloudinary object. 
```php
$cloudinary = new Cloudinary();
```

Here's an example of setting the configuration parameters programatically:

```php
$cloudinary = new Cloudinary(
    [
        'account' => [
            'cloud_name' => 'n07t21i7',
            'api_key'    => '123456789012345',
            'api_secret' => 'abcdeghijklmnopqrstuvwxyz12',
        ],
    ]
);
```


Another example of setting the configuration parameters by providing the CLOUDINARY_URL value to the constructor:

    $cloudinary = new Cloudinary('cloudinary://123456789012345:abcdeghijklmnopqrstuvwxyz12@n07t21i7');
    
### Embedding and transforming images

Any image uploaded to Cloudinary can be transformed and embedded using powerful view helper methods:

The following example generates the url for accessing an uploaded `sample` image while transforming it to fill a 100x150 rectangle:

```
$cloudinary->image('sample.jpg')->resize(Resize::fill(100, 150));
```

Another example, emedding a smaller version of an uploaded image while generating a 90x90 face detection based thumbnail: 

```
$cloudinary->image('woman.jpg')->resize(Resize::thumbnail(90, 90, Gravity::face());
```

Many other crop/resize modes can be found under `Resize::` static builder, use your IDE for auto-complete options.

### Upload

Assuming you have your Cloudinary configuration parameters defined (`cloud_name`, `key`, `secret`), uploading to Cloudinary is very simple.
    
The following example uploads a local JPG to the cloud: 

```
$cloudinary->uploadApi->upload('my_picture.jpg');
```
        
The uploaded image is assigned a randomly generated public ID. The image is immediately available for download through a CDN:

```
$cloudinary->image('abcfrmo8zul1mafopawefg.jpg');

# http://res.cloudinary.com/demo/image/upload/abcfrmo8zul1mafopawefg.jpg
```

You can also specify your own public ID:    

```
$cloudinary->uploadApi->upload('my_picture.jpg', ['public_id' => 'sample_remote']);

$cloudinary->image('sample_remote.jpg');

# http://res.cloudinary.com/demo/image/upload/sample_remote.jpg
```
### imageTag

Returns an html image tag pointing to Cloudinary.

Usage:

```
$cloudinary->imageTag('sample.png')->fill(100, 100);

# <img src='http://res.cloudinary.com/cloud_name/image/upload/c_fill,h_100,w_100/sample.png'/>
```

### uploadTag

Returns an html input field for direct image upload, to be used in conjunction with [cloudinary\_js package](https://github.com/cloudinary/cloudinary_js/). It integrates [jQuery-File-Upload widget](https://github.com/blueimp/jQuery-File-Upload) and provides all the necessary parameters for a direct upload.

Usage:

```
 $tag = new UploadTag('image', $cloudinary->configuration);
```

![](https://res.cloudinary.com/cloudinary/image/upload/see_more_bullet.png) **See [our documentation](https://cloudinary.com/documentation/java_image_upload#direct_uploading_from_the_browser) for plenty more options of uploading directly from the browser**.
  
## Additional resources ##########################################################

Additional resources are available at:

* [Website](https://cloudinary.com)
* [Interactive demo](https://demo.cloudinary.com/default)
* [Knowledge Base](https://support.cloudinary.com/hc/en-us) 
* [Documentation](https://cloudinary.com/documentation)
* [Image transformations documentation](https://cloudinary.com/documentation/image_transformations)
* [Upload API documentation](https://cloudinary.com/documentation/upload_images)

## Support

You can [open an issue through GitHub](https://github.com/cloudinary/cloudinary_php_v2/issues).

Stay tuned for updates, tips and tutorials: [Blog](https://cloudinary.com/blog), [Twitter](https://twitter.com/cloudinary), [Facebook](https://www.facebook.com/Cloudinary).

## Join the Community ##########################################################

Impact the product, hear updates, test drive new features and more! Join [here](https://www.facebook.com/groups/CloudinaryCommunity).

## Contributing ##########################################################

### Running Tests

Run all tests:

```bash
./vendor/bin/robo test
```

Run only unit tests:

```bash
./vendor/bin/robo test:unit
```

Run only integration tests:

```bash
./vendor/bin/robo test:integration
```

#### Test Options
All test commands also accept options such as `./vendor/bin/robo test:unit --coverage` to generate test coverage reports and `./vendor/bin/robo test:unit --dox` to output agile documentation from the tests.

## License #######################################################################

Released under the MIT license. 
