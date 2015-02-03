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
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test321/image/upload/test");
  }

  public function test_secure_distribution() {
    // should use default secure distribution if secure=TRUE        
    $options = array("secure" => TRUE);
    $this->cloudinary_url_assertion("test", $options, "https://res.cloudinary.com/test123/image/upload/test");
  }

  public function test_secure_distribution_overwrite() {
    // should allow overwriting secure distribution if secure=TRUE        
    $options = array("secure" => TRUE, "secure_distribution" => "something.else.com");
    $this->cloudinary_url_assertion("test", $options, "https://something.else.com/test123/image/upload/test");
  }

  public function test_secure_distibution() {
    // should take secure distribution from config if secure=TRUE
    Cloudinary::config(array("secure_distribution" => "config.secure.distribution.com"));
    $options = array("secure" => TRUE);
    $this->cloudinary_url_assertion("test", $options, "https://config.secure.distribution.com/test123/image/upload/test");
  }

  public function test_secure_akamai() {
    // should default to akamai if secure is given with private_cdn and no secure_distribution
    $options = array("secure" => TRUE, "private_cdn" => TRUE);
    $this->cloudinary_url_assertion("test", $options, "https://test123-res.cloudinary.com/image/upload/test");
  }

  public function test_secure_non_akamai() {
    // should not add cloud_name if private_cdn and secure non akamai secure_distribution
    $options = array("secure" => TRUE, "private_cdn" => TRUE, "secure_distribution" => "something.cloudfront.net");
    $this->cloudinary_url_assertion("test", $options, "https://something.cloudfront.net/image/upload/test");
  }

  public function test_http_private_cdn() {
    // should not add cloud_name if private_cdn and not secure
    $options = array("private_cdn" => TRUE);
    $this->cloudinary_url_assertion("test", $options, "http://test123-res.cloudinary.com/image/upload/test");
  }

  public function test_format() {
    // should use format from $options        
    $options = array("format" => "jpg");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/test.jpg");
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
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/g_center,o_20,p_a,q_0.4,r_3,x_1,y_2/test");
  }

  public function test_no_empty_options() {
    // should use x, y, width, height, crop, prefix and opacity from $options
    $options = array("x" => 0, "y" => '0', "width" => '', "height" => "", "crop" => ' ', "prefix" => false, "opacity" => null);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/x_0,y_0/test");
  }

  public function test_transformation_simple() {
    // should support named transformation        
    $options = array("transformation" => "blip");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/t_blip/test");
  }

  public function test_transformation_array() {
    // should support array of named transformations        
    $options = array("transformation" => array("blip", "blop"));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/t_blip.blop/test");
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
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/c_fill,x_100,y_100/test");
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
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/facebook/test");
  }

  public function test_resource_type() {
    // should use resource_type from $options
    $options = array("resource_type" => "raw");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/raw/upload/test");
  }

  public function test_ignore_http() {
    // should ignore http links only if type is not given
    $options = array();
    $this->cloudinary_url_assertion("http://test", $options, "http://test");
    $options = array("type" => "fetch");
    $this->cloudinary_url_assertion("http://test", $options, "http://res.cloudinary.com/test123/image/fetch/http://test");
  }

  public function test_fetch() {
    // should escape fetch urls
    $options = array("type" => "fetch");
    $this->cloudinary_url_assertion("http://blah.com/hello?a=b", $options, "http://res.cloudinary.com/test123/image/fetch/http://blah.com/hello%3Fa%3Db");
  }

  public function test_cname() {
    // should support extenal cname
    $options = array("cname" => "hello.com");
    $this->cloudinary_url_assertion("test", $options, "http://hello.com/test123/image/upload/test");
  }

  public function test_cname_subdomain() {
    // should support extenal cname with cdn_subdomain on
    $options = array("cname" => "hello.com", "cdn_subdomain" => TRUE);
    $this->cloudinary_url_assertion("test", $options, "http://a2.hello.com/test123/image/upload/test");
  }

  public function test_http_escape() {
    // should escape http urls
    $options = array("type" => "youtube");
    $result = $this->cloudinary_url_assertion("http://www.youtube.com/watch?v=d9NF2edxy-M", $options, "http://res.cloudinary.com/test123/image/youtube/http://www.youtube.com/watch%3Fv%3Dd9NF2edxy-M");
  }

  public function test_background() {
    // should support background
    $options = array("background" => "red");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/b_red/test");
    $options = array("background" => "#112233");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/b_rgb:112233/test");
  }

  public function test_default_image() {
    // should support default_image
    $options = array("default_image" => "default");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/d_default/test");
  }

  public function test_angle() {
    // should support angle
    $options = array("angle" => 12);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/a_12/test");
    $options = array("angle" => array("auto", 12));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/a_auto.12/test");
  }

  public function test_overlay() {
    // should support overlay
    $options = array("overlay" => "text:hello");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/l_text:hello/test");
    // should not pass width/height to html if overlay
    $options = array("overlay" => "text:hello", "width"=>100, "height"=>100);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/h_100,l_text:hello,w_100/test");
  }

  public function test_underlay() {
    // should support underlay
    $options = array("underlay" => "text:hello");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/u_text:hello/test");
    // should not pass width/height to html if underlay
    $options = array("underlay" => "text:hello", "width"=>100, "height"=>100);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/h_100,u_text:hello,w_100/test");
  }

  public function test_fetch_format() {
    // should support format for fetch urls
    $options = array("format" => "jpg", "type" => "fetch");
    $this->cloudinary_url_assertion("http://cloudinary.com/images/logo.png", $options, "http://res.cloudinary.com/test123/image/fetch/f_jpg/http://cloudinary.com/images/logo.png");
  }

  public function test_effect() {
    // should support effect
    $options = array("effect" => "sepia");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/e_sepia/test");
  }

  public function test_effect_with_array() {
    // should support effect with array
    $options = array("effect" => array("sepia", 10));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/e_sepia:10/test");
  }

  public function test_density() {
    // should support density
    $options = array("density" => 150);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/dn_150/test");
  }

  public function test_page() {
    // should support page
    $options = array("page" => 5);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/pg_5/test");
  }

  public function test_border() {
    // should support border
    $options = array("border" => array("width" => 5));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/bo_5px_solid_black/test");
    $options = array("border" => array("width" => 5, "color"=>"#ffaabbdd"));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/bo_5px_solid_rgb:ffaabbdd/test");
    $options = array("border" => "1px_solid_blue");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/bo_1px_solid_blue/test");
  }

  public function test_flags() {
    // should support flags
    $options = array("flags" => "abc");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/fl_abc/test");
    $options = array("flags" => array("abc", "def"));
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/upload/fl_abc.def/test");
  }

  public function test_cl_image_tag() {
    $tag = cl_image_tag("test", array("width"=>10, "height"=>10, "crop"=>"fill", "format"=>"png"));
    $this->assertEquals("<img src='http://res.cloudinary.com/test123/image/upload/c_fill,h_10,w_10/test.png' height='10' width='10'/>", $tag);
  }

  public function test_responsive_width() {
    // should add responsive width transformation
    $tag = cl_image_tag("hello", array("responsive_width"=>True, "format"=>"png"));
    $this->assertEquals("<img class='cld-responsive' data-src='http://res.cloudinary.com/test123/image/upload/c_limit,w_auto/hello.png'/>", $tag);

    $options = array("width"=>100, "height"=>100, "crop"=>"crop", "responsive_width"=>TRUE);
    $result = Cloudinary::cloudinary_url("test", $options);
    $this->assertEquals($options, array("responsive"=> TRUE));
    $this->assertEquals($result, "http://res.cloudinary.com/test123/image/upload/c_crop,h_100,w_100/c_limit,w_auto/test");
    Cloudinary::config(array("responsive_width_transformation"=>array("width"=>"auto", "crop"=>"pad")));
    $options = array("width"=>100, "height"=>100, "crop"=>"crop", "responsive_width"=>TRUE);
    $result = Cloudinary::cloudinary_url("test", $options);
    $this->assertEquals($options, array("responsive"=> TRUE));
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
    $this->cloudinary_url_assertion("folder/test", array(), "http://res.cloudinary.com/test123/image/upload/v1/folder/test");
    $this->cloudinary_url_assertion("folder/test", array("version"=>123), "http://res.cloudinary.com/test123/image/upload/v123/folder/test");
    $this->cloudinary_url_assertion("v1234/test", array(), "http://res.cloudinary.com/test123/image/upload/v1234/test");
  }

  public function test_shorten() {
    $options = array("shorten"=>TRUE);
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/iu/test");

    $options = array("shorten"=>TRUE, "type"=>"private");
    $this->cloudinary_url_assertion("test", $options, "http://res.cloudinary.com/test123/image/private/test");
  }

  public function test_cl_sprite_tag() {
    $url = cl_sprite_tag("mytag", array("crop"=>"fill", "width"=>10, "height"=>10));
    $this->assertEquals("<link rel='stylesheet' type='text/css' href='http://res.cloudinary.com/test123/image/sprite/c_fill,h_10,w_10/mytag.css'>", $url);
  }

  public function test_signed_url() {
    // should correctly sign a url
    $this->cloudinary_url_assertion("image.jpg", array("version"=> 1234, "transformation"=> array("crop"=> "crop", "width"=> 10, "height"=> 20), "sign_url"=> TRUE), "http://res.cloudinary.com/test123/image/upload/s--Ai4Znfl3--/c_crop,h_20,w_10/v1234/image.jpg");
    $this->cloudinary_url_assertion("image.jpg", array("version"=> 1234, "sign_url"=> TRUE), "http://res.cloudinary.com/test123/image/upload/s----SjmNDA--/v1234/image.jpg");
    $this->cloudinary_url_assertion("image.jpg", array("transformation"=> array("crop"=> "crop", "width"=> 10, "height"=> 20), "sign_url"=> TRUE), "http://res.cloudinary.com/test123/image/upload/s--Ai4Znfl3--/c_crop,h_20,w_10/image.jpg");
    $this->cloudinary_url_assertion("image.jpg", array("transformation"=> array("crop"=> "crop", "width"=> 10, "height"=> 20), "type"=> "authenticated", "sign_url"=> TRUE), "http://res.cloudinary.com/test123/image/authenticated/s--Ai4Znfl3--/c_crop,h_20,w_10/image.jpg");
    $this->cloudinary_url_assertion("http://google.com/path/to/image.png", array("type"=> "fetch", "version"=> 1234, "sign_url"=> TRUE), "http://res.cloudinary.com/test123/image/fetch/s--hH_YcbiS--/v1234/http://google.com/path/to/image.png");
  }

  public function test_escape_public_id() {
    //should escape public_ids
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

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_url_suffix_in_shared() {
    // should disallow url_suffix in shared distribution
    $options = array("url_suffix"=>"hello");
    Cloudinary::cloudinary_url("test", $options);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_url_suffix_with_non_upload_types() {
    //should disallow url_suffix in non upload types
    $options = array("url_suffix"=>"hello", "private_cdn"=>TRUE, "type"=>"facebook");
    Cloudinary::cloudinary_url("test", $options);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_suffix_with_dot(){
    //should disallow url_suffix with .
    $options = array("url_suffix"=>"hello/world", "private_cdn"=>TRUE);
    Cloudinary::cloudinary_url("test", $options);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_suffix_with_slash(){
    //should disallow url_suffix with /
    $options = array("url_suffix"=>"hello/world", "private_cdn"=>TRUE);
    Cloudinary::cloudinary_url("test", $options);
  }

  
  public function test_url_suffix_for_private_cdn(){
    //should support url_suffix for private_cdn
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "private_cdn"=>TRUE), "http://test123-res.cloudinary.com/images/test/hello");
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "transformation" => array("angle"=>0), "private_cdn"=>TRUE), "http://test123-res.cloudinary.com/images/a_0/test/hello");
  }

  public function test_format_after_url_suffix(){
    //should put format after url_suffix
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "private_cdn"=>TRUE, "format"=>"jpg"), "http://test123-res.cloudinary.com/images/test/hello.jpg");
  }

  public function test_dont_sign_the_url_suffix(){
    //should not sign the url_suffix
    $options = array("format"=>"jpg", "sign_url"=>TRUE);
    preg_match('/s--[0-9A-Za-z_-]{8}--/', Cloudinary::cloudinary_url("test", $options), $matches);
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "private_cdn"=>TRUE, "format"=>"jpg", "sign_url"=>TRUE), "http://test123-res.cloudinary.com/images/" . $matches[0] . "/test/hello.jpg");

    $options = array("format"=>"jpg", "angle"=>0, "sign_url"=>TRUE);
    preg_match('/s--[0-9A-Za-z_-]{8}--/', Cloudinary::cloudinary_url("test", $options), $matches);
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "private_cdn"=>TRUE, "format"=>"jpg", "transformation" => array("angle"=>0), "sign_url"=>TRUE), "http://test123-res.cloudinary.com/images/" . $matches[0] . "/a_0/test/hello.jpg");
  }

  public function test_url_suffix_for_raw(){
    //should support url_suffix for raw uploads
    $this->cloudinary_url_assertion("test", array("url_suffix"=>"hello", "private_cdn"=>TRUE, "resource_type"=>"raw"), "http://test123-res.cloudinary.com/files/test/hello");
  }

  public function test_allow_use_root_path_in_shared() {

    $this->cloudinary_url_assertion("test", array("use_root_path"=>TRUE, "private_cdn"=>FALSE), "http://res.cloudinary.com/test123/test");
    $this->cloudinary_url_assertion("test", array("use_root_path"=>TRUE, "private_cdn"=>FALSE, "transformation"=>array("angle"=>0)), "http://res.cloudinary.com/test123/a_0/test");
  }

  public function test_use_root_path_for_private_cdn() {
    //should support use_root_path for private_cdn
    $this->cloudinary_url_assertion("test", array("use_root_path"=>TRUE, "private_cdn"=>TRUE), "http://test123-res.cloudinary.com/test");
    $this->cloudinary_url_assertion("test", array("use_root_path"=>TRUE, "private_cdn"=>TRUE, "transformation"=>array("angle"=>0)), "http://test123-res.cloudinary.com/a_0/test");
  }

  public function test_use_root_path_with_url_suffix_for_private_cdn() {
    //should support use_root_path together with url_suffix for private_cdn
    $this->cloudinary_url_assertion("test", array("use_root_path"=>TRUE, "url_suffix"=>"hello", "private_cdn"=>TRUE), "http://test123-res.cloudinary.com/test/hello");
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_use_root_path_if_not_image_upload_1() {
    //should disallow use_root_path if not image/upload
    $options = array("use_root_path"=>TRUE, "private_cdn"=>TRUE, "type"=>"facebook");
    Cloudinary::cloudinary_url("test", $options);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function test_disallow_use_root_path_if_not_image_upload_2() {
    //should disallow use_root_path if not image/upload
    $options = array("use_root_path"=>TRUE, "private_cdn"=>TRUE, "resource_type"=>"raw");
    Cloudinary::cloudinary_url("test", $options);
  }

  private function cloudinary_url_assertion($source, $options, $expected) {
    $url = Cloudinary::cloudinary_url($source, $options);
    $this->assertEquals(array(), $options);
    $this->assertEquals($expected, $url);
  }
}
?>

