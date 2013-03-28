<?php
$base = realpath(dirname(__FILE__).'/../');
require_once($base . DIRECTORY_SEPARATOR . 'src/Cloudinary.php');
require_once($base . DIRECTORY_SEPARATOR . 'src/Uploader.php');
require_once($base . DIRECTORY_SEPARATOR . 'src/Api.php');

class UploaderTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        Cloudinary::reset_config();
        if (!Cloudinary::config_get("api_secret")) {
          $this->markTestSkipped('Please setup environment for Upload test to run');
        }
    }

    public function test_upload() {
        $result = Cloudinary\Uploader::upload("tests/logo.png");
        $this->assertEquals($result["width"], 241);
        $this->assertEquals($result["height"], 51);
        $expected_signature = Cloudinary::api_sign_request(array("public_id"=>$result["public_id"], "version"=>$result["version"]), Cloudinary::config_get("api_secret"));
        $this->assertEquals($result["signature"], $expected_signature);
    }

    public function test_explicit() {
        $result = Cloudinary\Uploader::explicit("cloudinary", array("type"=>"twitter_name", "eager"=>array("crop"=>"scale", "width"=>"2.0")));        
        $url = cloudinary_url("cloudinary", array("type"=>"twitter_name", "crop"=>"scale", "width"=>"2.0", "format"=>"png", "version"=>$result["version"]));
        $this->assertEquals($result["eager"][0]["url"], $url);        
    }

    public function test_eager() {
        Cloudinary\Uploader::upload("tests/logo.png", array("eager"=>array("crop"=>"scale", "width"=>"2.0")));
    }

    public function test_headers() {
        Cloudinary\Uploader::upload("tests/logo.png", array("headers"=>array("Link: 1")));
        Cloudinary\Uploader::upload("tests/logo.png", array("headers"=>array("Link" => "1")));
    }

    public function test_text() {
        $result = Cloudinary\Uploader::text("hello world");
        $this->assertGreaterThan(1, $result["width"]);
        $this->assertGreaterThan(1, $result["height"]);
    }

    public function test_tags() {
        $api = new \Cloudinary\Api();      
        $result = Cloudinary\Uploader::upload("tests/logo.png");
        Cloudinary\Uploader::add_tag("tag1", $result["public_id"]);
        Cloudinary\Uploader::add_tag("tag2", $result["public_id"]);
        $info = $api->resource($result["public_id"]);
        $this->assertEquals($info["tags"], array("tag1", "tag2"));
        Cloudinary\Uploader::remove_tag("tag1", $result["public_id"]);
        $info = $api->resource($result["public_id"]);
        $this->assertEquals($info["tags"], array("tag2"));
        Cloudinary\Uploader::replace_tag("tag3", $result["public_id"]);
        $info = $api->resource($result["public_id"]);
        $this->assertEquals($info["tags"], array("tag3"));
    }
    
    public function test_cl_form_tag() {
        Cloudinary::config(array("cloud_name"=>"test123", "secure_distribution" => NULL, "private_cdn" => FALSE, "api_key" => "1234"));

        $form = cl_form_tag("http://callback.com", array("public_id"=>"hello", "form"=>array("class"=>"uploader")));
        $this->assertRegexp("/<form enctype='multipart\/form-data' action='https:\/\/api.cloudinary.com\/v1_1\/test123\/image\/upload' method='POST' class='uploader'>\n<input name='timestamp' type='hidden' value='\d+'\/>\n<input name='public_id' type='hidden' value='hello'\/>\n<input name='signature' type='hidden' value='[0-9a-f]+'\/>\n<input name='api_key' type='hidden' value='1234'\/>\n<\/form>/", $form);
    }
    public function test_cl_image_upload_tag() {
        Cloudinary::config(array("cloud_name"=>"test123", "secure_distribution" => NULL, "private_cdn" => FALSE, "api_key" => "1234"));

        $tag = cl_image_upload_tag("image", array("public_id"=>"hello", "html"=>array("class"=>"uploader")));
        $this->assertRegexp("/<input class='uploader cloudinary-fileupload' data-cloudinary-field='image' data-form-data='{\"timestamp\":\d+,\"public_id\":\"hello\",\"signature\":\"[0-9a-f]+\",\"api_key\":\"1234\"}' data-url='https:\/\/api.cloudinary.com\/v1_1\/test123\/auto\/upload' name='file' type='file'\/>/", $tag);
    }
}
?>
