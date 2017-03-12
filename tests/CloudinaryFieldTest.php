<?php

namespace CloudinaryTests;

use Cloudinary\Cloudinary;
use Cloudinary\CloudinaryField;

class CloudinaryFieldTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Cloudinary::config(array(
            'cloud_name' => 'test123',
            'secure_distribution' => null,
            'private_cdn' => false
        ));
    }

    public function testCloudinaryUrlFromCloudinaryField()
    {
        // [<resource_type>/][<image_type>/][v<version>/]<public_id>[.<format>][#<signature>]

        // should use cloud_name from config
        $result = Cloudinary::cloudinaryUrl(new CloudinaryField('test'));
        $this->assertEquals('http://res.cloudinary.com/test123/image/upload/test', $result);

        // should ignore signature
        $result = Cloudinary::cloudinaryUrl(new CloudinaryField('test#signature'));
        $this->assertEquals('http://res.cloudinary.com/test123/image/upload/test', $result);

        $result = Cloudinary::cloudinaryUrl(new CloudinaryField('rss/imgt/v123/test.jpg'));
        $this->assertEquals('http://res.cloudinary.com/test123/rss/imgt/v123/test.jpg', $result);
    }
}
