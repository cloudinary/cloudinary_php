<?php
require "cloudinary.php" ;
class CloudinaryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        Cloudinary::config(array("cloud_name"=>"test123", "secure_distribution" => NULL, "private_cdn" => FALSE));
    }

    public function test_cloud_name() {
        // should use cloud_name from config
        $result = Cloudinary::cloudinary_url("test");
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/test", $result);
    }

    public function test_cloud_name_options() {
        // should allow overriding cloud_name in $options
        $options = array("cloud_name" => "test321");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test321/image/upload/test", $result);
    }
    
    public function test_secure_distribution() {
        // should use default secure distribution if secure=TRUE        
        $options = array("secure" => TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("https://d3jpl91pxevbkh.cloudfront.net/test123/image/upload/test", $result);
    }

    public function test_secure_distribution_overwrite() {
        // should allow overwriting secure distribution if secure=TRUE        
        $options = array("secure" => TRUE, "secure_distribution" => "something.else.com");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("https://something.else.com/test123/image/upload/test", $result);
    }

    public function test_secure_distibution() {
        // should take secure distribution from config if secure=TRUE
        Cloudinary::config(array("secure_distribution" => "config.secure.distribution.com"));
        $options = array("secure" => TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("https://config.secure.distribution.com/test123/image/upload/test", $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_missing_secure_distribution() {
        // should raise exception if secure is given with private_cdn and no secure_distribution
        Cloudinary::config(array("private_cdn" => TRUE));
        cloudinary_url("test", array("secure"=>TRUE));
    }

    public function test_format() {
        // should use format from $options        
        $options = array("format" => "jpg");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/test.jpg", $result);
    }

    public function test_crop() {
        // should use width and height from $options only if crop is given
        $options = array("width" => 100, "height" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/test", $result);
        $this->assertEquals(array("width" => 100, "height" => 100), $options);
        $options = array("width" => 100, "height" => 100, "crop" => "crop");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100, "height" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_crop,h_100,w_100/test", $result);
    }
    
    public function test_various_options() {
        // should use x, y, radius, prefix, gravity and quality from $options        
        $options = array("x" => 1, "y" => 2, "radius" => 3, "gravity" => "center", "quality" => 0.4, "prefix" => "a");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/g_center,p_a,q_0.4,r_3,x_1,y_2/test", $result);
    }
    
    public function test_transformation_simple() {
        // should support named tranformation        
        $options = array("transformation" => "blip");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/t_blip/test", $result);
    }

    public function test_transformation_array() {
        // should support array of named tranformations        
        $options = array("transformation" => array("blip", "blop"));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/t_blip.blop/test", $result);
    }

    public function test_base_transformations() {
        // should support base tranformation        
        $options = array("transformation" => array("x" => 100, "y" => 100, "crop" => "fill"), "crop" => "crop", "width" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_fill,x_100,y_100/c_crop,w_100/test", $result);
    }

    public function test_base_transformation_array() {
        // should support array of base tranformations        
        $options = array("transformation" => array(array("x" => 100, "y" => 100, "width" => 200, "crop" => "fill"), array("radius" => 10)), "crop" => "crop", "width" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_fill,w_200,x_100,y_100/r_10/c_crop,w_100/test", $result);
    }

    public function test_no_empty_transformation() {
        // should not include empty tranformations        
        $options = array("transformation" => array(array(), array("x" => 100, "y" => 100, "crop" => "fill"), array()));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_fill,x_100,y_100/test", $result);
    }

    public function test_size() {
        // should support size        
        $options = array("size" => "10x10", "crop" => "crop");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => "10", "height" => "10"), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_crop,h_10,w_10/test", $result);
    }

    public function test_type() {
        // should use type from $options
        $options = array("type" => "facebook");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/facebook/test", $result);
    }

    public function test_resource_type() {
        // should use resource_type from $options
        $options = array("resource_type" => "raw");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/raw/upload/test", $result);
    }

    public function test_ignore_http() {
        // should ignore http links only if type is not given or is asset
        $options = array();
        $result = Cloudinary::cloudinary_url("http://test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://test", $result);
        $options = array("type" => "asset");
        $result = Cloudinary::cloudinary_url("http://test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://test", $result);
        $options = array("type" => "fetch");
        $result = Cloudinary::cloudinary_url("http://test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/fetch/http://test", $result);
    }

    public function test_fetch() {
        // should escape fetch urls
        $options = array("type" => "fetch");
        $result = Cloudinary::cloudinary_url("http://blah.com/hello?a=b", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/fetch/http://blah.com/hello%3Fa%3Db", $result);
    }

    public function test_cname() {
        // should support extenal cname
        $options = array("cname" => "hello.com");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://hello.com/test123/image/upload/test", $result);
    }

    public function test_cname_subdomain() {
        // should support extenal cname with cdn_subdomain on
        $options = array("cname" => "hello.com", "cdn_subdomain" => TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://a2.hello.com/test123/image/upload/test", $result);
    }

    public function test_http_escape() {
        // should escape http urls
        $options = array("type" => "youtube");
        $result = Cloudinary::cloudinary_url("http://www.youtube.com/watch?v=d9NF2edxy-M", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/youtube/http://www.youtube.com/watch%3Fv%3Dd9NF2edxy-M", $result);
    }

    public function test_background() {
        // should support background
        $options = array("background" => "red");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/b_red/test", $result);
        $options = array("background" => "#112233");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/b_rgb:112233/test", $result);
    }
    
    public function test_default_image() {
        // should support default_image
        $options = array("default_image" => "default");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/d_default/test", $result);
    }

    public function test_angle() {
        // should support angle
        $options = array("angle" => 12);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/a_12/test", $result);
        $options = array("angle" => array("auto", 12));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/a_auto.12/test", $result);
    }

    public function test_overlay() {
        // should support overlay
        $options = array("overlay" => "text:hello");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/l_text:hello/test", $result);
        // should not pass width/height to html if overlay
        $options = array("overlay" => "text:hello", "width"=>100, "height"=>100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/h_100,l_text:hello,w_100/test", $result);
    }

    public function test_underlay() {
        // should support underlay
        $options = array("underlay" => "text:hello");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/u_text:hello/test", $result);
        // should not pass width/height to html if underlay
        $options = array("underlay" => "text:hello", "width"=>100, "height"=>100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/h_100,u_text:hello,w_100/test", $result);
    }

    public function test_fetch_format() {
        // should support format for fetch urls
        $options = array("format" => "jpg", "type" => "fetch");
        $result = Cloudinary::cloudinary_url("http://cloudinary.com/images/logo.png", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/fetch/f_jpg/http://cloudinary.com/images/logo.png", $result);
    }

    public function test_effect() {
        // should support effect
        $options = array("effect" => "sepia");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/e_sepia/test", $result);
    }

    public function test_effect_with_array() {
        // should support effect with array
        $options = array("effect" => array("sepia", 10));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/e_sepia:10/test", $result);
    }

    public function test_density() {
        // should support density
        $options = array("density" => 150);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/dn_150/test", $result);
    }

    public function test_page() {
        // should support page
        $options = array("page" => 5);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/pg_5/test", $result);
    }

    public function test_border() {
        // should support border
        $options = array("border" => array("width" => 5));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/bo_5px_solid_black/test", $result);
        $options = array("border" => array("width" => 5, "color"=>"#ffaabbdd"));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/bo_5px_solid_rgb:ffaabbdd/test", $result);
        $options = array("border" => "1px_solid_blue");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/bo_1px_solid_blue/test", $result);
    }

    public function test_flags() {
        // should support flags
        $options = array("flags" => "abc");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/fl_abc/test", $result);
        $options = array("flags" => array("abc", "def"));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/fl_abc.def/test", $result);
    }
    
    public function test_cl_image_tag() {
        $tag = cl_image_tag("test", array("width"=>10, "height"=>10, "crop"=>"fit", "format"=>"png"));
        $this->assertEquals("<img src='http://res.cloudinary.com/test123/image/upload/c_fit,h_10,w_10/test.png' height='10' width='10'/>", $tag);
    }
}
?>

