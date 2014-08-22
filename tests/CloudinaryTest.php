<?php
$base = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..');
require_once(join(DIRECTORY_SEPARATOR, array($base, 'src', 'Cloudinary.php')));
class CloudinaryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        Cloudinary::config(array("cloud_name"=>"test123", "api_secret"=>"b",  "secure_distribution" => NULL, "private_cdn" => FALSE));
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
        $this->assertEquals("https://res.cloudinary.com/test123/image/upload/test", $result);
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

    public function test_secure_akamai() {
        // should default to akamai if secure is given with private_cdn and no secure_distribution
        $options = array("secure" => TRUE, "private_cdn" => TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("https://test123-res.cloudinary.com/image/upload/test", $result);
    }

    public function test_secure_non_akamai() {
        // should not add cloud_name if private_cdn and secure non akamai secure_distribution
        $options = array("secure" => TRUE, "private_cdn" => TRUE, "secure_distribution" => "something.cloudfront.net");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("https://something.cloudfront.net/image/upload/test", $result);
    }

    public function test_http_private_cdn() {
        // should not add cloud_name if private_cdn and not secure
        $options = array("private_cdn" => TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://test123-res.cloudinary.com/image/upload/test", $result);
    }

    public function test_format() {
        // should use format from $options        
        $options = array("format" => "jpg");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/test.jpg", $result);
    }

    public function test_crop() {
        // should use width and height from $options even if crop is not given
        $options = array("width" => 100, "height" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/h_100,w_100/test", $result);
        $this->assertEquals(array("width" => 100, "height" => 100), $options);
        $options = array("width" => 100, "height" => 100, "crop" => "crop");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100, "height" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_crop,h_100,w_100/test", $result);
    }
    
    public function test_various_options() {
        // should use x, y, radius, prefix, gravity and quality from $options        
        $options = array("x" => 1, "y" => 2, "radius" => 3, "gravity" => "center", "quality" => 0.4, "prefix" => "a", "opacity" => 20);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/g_center,o_20,p_a,q_0.4,r_3,x_1,y_2/test", $result);
    }

    public function test_no_empty_options() {
        // should use x, y, width, height, crop, prefix and opacity from $options
        $options = array("x" => 0, "y" => '0', "width" => '', "height" => "", "crop" => ' ', "prefix" => false, "opacity" => null);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/x_0,y_0/test", $result);
    }
    
    public function test_transformation_simple() {
        // should support named transformation        
        $options = array("transformation" => "blip");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/t_blip/test", $result);
    }

    public function test_transformation_array() {
        // should support array of named transformations        
        $options = array("transformation" => array("blip", "blop"));
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/t_blip.blop/test", $result);
    }

    public function test_base_transformations() {
        // should support base transformation        
        $options = array("transformation" => array("x" => 100, "y" => 100, "crop" => "fill"), "crop" => "crop", "width" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_fill,x_100,y_100/c_crop,w_100/test", $result);
    }

    public function test_base_transformation_array() {
        // should support array of base transformations        
        $options = array("transformation" => array(array("x" => 100, "y" => 100, "width" => 200, "crop" => "fill"), array("radius" => 10)), "crop" => "crop", "width" => 100);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array("width" => 100), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/c_fill,w_200,x_100,y_100/r_10/c_crop,w_100/test", $result);
    }

    public function test_no_empty_transformation() {
        // should not include empty transformations        
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
        $tag = cl_image_tag("test", array("width"=>10, "height"=>10, "crop"=>"fill", "format"=>"png"));
        $this->assertEquals("<img src='http://res.cloudinary.com/test123/image/upload/c_fill,h_10,w_10/test.png' height='10' width='10'/>", $tag);
    }

    public function test_responsive_width() {
        // should add responsive width transformation
        $tag = cl_image_tag("hello", array("responsive_width"=>True, "format"=>"png"));
        $this->assertEquals("<img class='cld-responsive' data-src='http://res.cloudinary.com/test123/image/upload/c_limit,w_auto/hello.png'/>", $tag);

        $options = array("width"=>100, "height"=>100, "crop"=>"crop", "responsive_width"=>true);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals($options, array("responsive"=> true));
        $this->assertEquals($result, "http://res.cloudinary.com/test123/image/upload/c_crop,h_100,w_100/c_limit,w_auto/test");
        Cloudinary::config(array("responsive_width_transformation"=>array("width"=>"auto", "crop"=>"pad")));
        $options = array("width"=>100, "height"=>100, "crop"=>"crop", "responsive_width"=>true);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals($options, array("responsive"=> true));
        $this->assertEquals($result, "http://res.cloudinary.com/test123/image/upload/c_crop,h_100,w_100/c_pad,w_auto/test");
    }

    public function test_width_auto() {
        // should support width=auto
        $tag = cl_image_tag("hello", array("width"=>"auto", "crop"=>"limit", "format"=>"png"));
        $this->assertEquals("<img class='cld-responsive' data-src='http://res.cloudinary.com/test123/image/upload/c_limit,w_auto/hello.png'/>", $tag);
    }

    public function test_dpr_auto() {
        // should support width=auto
        $tag = cl_image_tag("hello", array("dpr"=>"auto", "format"=>"png"));
        $this->assertEquals("<img class='cld-hidpi' data-src='http://res.cloudinary.com/test123/image/upload/dpr_auto/hello.png'/>", $tag);
    }

    public function test_folder_version() {
        // should add version if public_id contains /
        $result = Cloudinary::cloudinary_url("folder/test");
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/v1/folder/test", $result);
        $options = array("version"=>123);
        $result = Cloudinary::cloudinary_url("folder/test", $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/v123/folder/test", $result);
        $result = Cloudinary::cloudinary_url("v1234/test");
        $this->assertEquals("http://res.cloudinary.com/test123/image/upload/v1234/test", $result);
    }

    public function test_shorten() {
        $options = array("shorten"=>TRUE);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/iu/test", $result);

        $options = array("shorten"=>TRUE, "type"=>"private");
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals(array(), $options);
        $this->assertEquals("http://res.cloudinary.com/test123/image/private/test", $result);
    }
    
    public function test_cl_sprite_tag() {
        $url = cl_sprite_tag("mytag", array("crop"=>"fill", "width"=>10, "height"=>10));
        $this->assertEquals("<link rel='stylesheet' type='text/css' href='http://res.cloudinary.com/test123/image/sprite/c_fill,h_10,w_10/mytag.css'>", $url);
    }
    
    public function test_signed_url() {
      // should correctly sign a url
      $ooptions = $options = array("version" => 1234, "transformation" => array("crop" => "crop", "width" => 10, "height" => 20), "sign_url" => TRUE);
    	$expected = "http://res.cloudinary.com/test123/image/upload/s--MaRXzoEC--/c_crop,h_20,w_10/v1234/image.jpg";
    	$actual = Cloudinary::cloudinary_url("image.jpg", $options);
    	$this->assertEquals($expected, $actual);

    	$expected = "http://res.cloudinary.com/test123/image/upload/s--ZlgFLQcO--/v1234/image.jpg";
      $options2 = $ooptions;
      unset($options2["transformation"]);
    	$actual = Cloudinary::cloudinary_url("image.jpg", $options2);
    	$this->assertEquals($expected, $actual);

    	$expected = "http://res.cloudinary.com/test123/image/upload/s--Ai4Znfl3--/c_crop,h_20,w_10/image.jpg";
      $options3 = $ooptions;
      unset($options3["version"]);
    	$actual = Cloudinary::cloudinary_url("image.jpg", $options3);
    	$this->assertEquals($expected, $actual);
    }

    public function test_escape_public_id() {
        # should escape public_ids
        $tests = array(
            "a b" => "a%20b",
            "a+b" => "a%2Bb",
            "a%20b" => "a%20b",
            "a-b" => "a-b",
            "a??b" => "a%3F%3Fb",
            "parentheses(interject)" => "parentheses%28interject%29");
        foreach ($tests as $source => $target) {
            $url = Cloudinary::cloudinary_url($source);
            $this->assertEquals("http://res.cloudinary.com/test123/image/upload/$target", $url);                      
        }
    }    
}
?>

