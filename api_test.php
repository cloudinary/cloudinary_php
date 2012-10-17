<?php

require "cloudinary.php" ;
require "uploader.php" ;
require "api.php" ;
class ApiTest extends PHPUnit_Framework_TestCase {
  static $initialized = FALSE;  
  public function setUp() {
    if (!Cloudinary::config_get("api_secret")) {
      $this->markTestSkipped('Please setup environment for API test to run');
    }
    $this->api = new \Cloudinary\Api();
    if (self::$initialized) return;
    self::$initialized = TRUE;
    try {
      $this->api->delete_resources(array("api_test", "api_test2", "api_test3"));
    } catch (Exception $e) {}
    try {
      $this->api->delete_transformation("api_test_transformation");
    } catch (Exception $e) {}
    try {
      $this->api->delete_transformation("api_test_transformation2");
    } catch (Exception $e) {}
    \Cloudinary\Uploader::upload("tests/logo.png", 
      array("public_id"=>"api_test", "tags"=>"api_test_tag", "eager"=>array("transformation"=>array("width"=>100,"crop"=>"scale"))));
    \Cloudinary\Uploader::upload("tests/logo.png", 
      array("public_id"=>"api_test2", "tags"=>"api_test_tag", "eager"=>array("transformation"=>array("width"=>100,"crop"=>"scale"))));
  }
   
  function find_by_attr($elements, $attr, $value) {
    foreach ($elements as $element) {
      if ($element[$attr] == $value) return $element;
    }
    return NULL;
  } 
  
  function test01_resource_types() {    // should allow listing resource_types
    $result = $this->api->resource_types(); 
    $this->assertContains("image", $result["resource_types"]);
  }
  
  function test02_resources() {
    // should allow listing resources 
    $result = $this->api->resources();
    $resource = $this->find_by_attr($result["resources"], "public_id", "api_test"); 
    $this->assertNotEquals($resource, NULL);    
    $this->assertEquals($resource["type"], "upload");
  }
  
  function test03_resources_cursor() {
    // should allow listing resources with cursor
    $result = $this->api->resources(array("max_results"=>1));
    $this->assertNotEquals($result["resources"], NULL);    
    $this->assertEquals(count($result["resources"]), 1);
    $this->assertNotEquals($result["next_cursor"], NULL);

    $result2 = $this->api->resources(array("max_results"=>1, "next_cursor"=>$result["next_cursor"]));
    $this->assertNotEquals($result2["resources"], NULL);    
    $this->assertEquals(count($result2["resources"]), 1);
    $this->assertNotEquals($result2["resources"][0]["public_id"], $result["resources"][0]["public_id"]); 
  }
  
  function test04_resources_by_type() {
    // should allow listing resources by type 
    $result = $this->api->resources(array("type"=>"upload"));
    $resource = $this->find_by_attr($result["resources"], "public_id", "api_test"); 
    $this->assertNotEquals($resource, NULL);
  }

  function test05_resources_by_prefix() {
    // should allow listing resources by prefix 
    $result = $this->api->resources(array("type"=>"upload", "prefix"=>"api_test"));
    $func = function($resource) {
        return $resource["public_id"];
    };

    $public_ids = array_map($func, $result["resources"]);  
    $this->assertContains("api_test", $public_ids);
    $this->assertContains("api_test2", $public_ids);
  }
  
  function test06_resources_tag() {
    // should allow listing resources by tag 
    $result = $this->api->resources_by_tag("api_test_tag");
    $resource = $this->find_by_attr($result["resources"], "public_id", "api_test"); 
    $this->assertNotEquals($resource, NULL);
  }
  
  function test07_resource_metadata() {
    // should allow get resource metadata 
    $resource = $this->api->resource("api_test");
    $this->assertNotEquals($resource, NULL); 
    $this->assertEquals($resource["public_id"], "api_test");
    $this->assertEquals($resource["bytes"], 3381);
    $this->assertEquals(count($resource["derived"]), 1);
  }
  
  function test08_delete_derived() {
    // should allow deleting derived resource 
    \Cloudinary\Uploader::upload("tests/logo.png", array("public_id"=>"api_test3", "eager"=>array("transformation"=>array("width"=> 101,"crop" => "scale"))));    
    $resource = $this->api->resource("api_test3");
    $this->assertNotEquals($resource, NULL);    
    $this->assertEquals(count($resource["derived"]), 1);
    $derived_resource_id = $resource["derived"][0]["id"];
    $this->api->delete_derived_resources(array($derived_resource_id));
    $resource = $this->api->resource("api_test3");
    $this->assertNotEquals($resource, NULL);    
    $this->assertEquals(count($resource["derived"]), 0);
  }
  
  /**
   * @expectedException \Cloudinary\Api\NotFound
   */
  function test09_delete_resources() {
    // should allow deleting resources 
    \Cloudinary\Uploader::upload("tests/logo.png", array("public_id"=>"api_test3"));
    $resource = $this->api->resource("api_test3");
    $this->assertNotEquals($resource, NULL);    
    $this->api->delete_resources(array("apit_test", "api_test2", "api_test3"));
    $this->api->resource("api_test3");
  }

  /**
   * @expectedException \Cloudinary\Api\NotFound
   */
  function test09a_delete_resources_by_prefix() {
    // should allow deleting resources 
    \Cloudinary\Uploader::upload("tests/logo.png", array("public_id"=>"api_test_by_prefix"));
    $resource = $this->api->resource("api_test_by_prefix");
    $this->assertNotEquals($resource, NULL);    
    $this->api->delete_resources_by_prefix("api_test_by");
    $this->api->resource("api_test_by_prefix");
  }

  /**
   * @expectedException \Cloudinary\Api\NotFound
   */
  function test09b_delete_resources_by_tag() {
    // should allow deleting resources 
    \Cloudinary\Uploader::upload("tests/logo.png", array("public_id"=>"api_test4", "tags"=>array("api_test_tag_for_delete")));
    $resource = $this->api->resource("api_test4");
    $this->assertNotEquals($resource, NULL);    
    $this->api->delete_resources_by_tag("api_test4");
    $this->api->resource("api_test4");
  }
  
  function test10_tags() {
    // should allow listing tags
    $result = $this->api->tags(); 
    $tags = $result["tags"];
    $this->assertContains("api_test_tag", $tags);
  }
  
  function test11_tags_prefix() {
    // should allow listing tag by prefix
    $result = $this->api->tags(array("prefix"=>"api_test")); 
    $tags = $result["tags"];
    $this->assertContains("api_test_tag", $tags);
    $result = $this->api->tags(array("prefix"=>"api_test_no_such_tag"));
    $tags = $result["tags"];
    $this->assertEquals(count($tags), 0);
  }

  function test12_transformations() {
    // should allow listing transformations 
    $result = $this->api->transformations();    
    $transformation = $this->find_by_attr($result["transformations"], "name", "c_scale,w_100");

    $this->assertNotEquals($transformation, NULL);    
    $this->assertEquals($transformation["used"], TRUE);
  }
  
  function test13_transformation_metadata() {
    // should allow getting transformation metadata 
    $transformation = $this->api->transformation("c_scale,w_100");
    $this->assertNotEquals($transformation, NULL);    
    $this->assertEquals($transformation["info"], array(array("crop"=> "scale", "width"=> 100)));
    $transformation = $this->api->transformation(array("crop"=> "scale", "width"=> 100));
    $this->assertNotEquals($transformation, NULL);   
    $this->assertEquals($transformation["info"], array(array("crop"=> "scale", "width"=> 100)));
  }
  
  function test14_transformation_update() {
    // should allow updating transformation allowed_for_strict 
    $this->api->update_transformation("c_scale,w_100", array("allowed_for_strict"=>TRUE));
    $transformation = $this->api->transformation("c_scale,w_100");
    $this->assertNotEquals($transformation, NULL);    
    $this->assertEquals($transformation["allowed_for_strict"], TRUE);
    $this->api->update_transformation("c_scale,w_100", array("allowed_for_strict"=>FALSE));
    $transformation = $this->api->transformation("c_scale,w_100");
    $this->assertNotEquals($transformation, NULL);
    $this->assertEquals($transformation["allowed_for_strict"], FALSE);
  }

  function test15_transformation_create() {
    // should allow creating named transformation 
    $this->api->create_transformation("api_test_transformation", array("crop" => "scale", "width" => 102));
    $transformation = $this->api->transformation("api_test_transformation");
    $this->assertNotEquals($transformation, NULL);    
    $this->assertEquals($transformation["allowed_for_strict"], TRUE);
    $this->assertEquals($transformation["info"], array(array("crop"=> "scale", "width"=> 102)));
    $this->assertEquals($transformation["used"], FALSE);
  }
  
  function test16a_transformation_delete() {
    // should allow deleting named transformation 
    $this->api->create_transformation("api_test_transformation2", array("crop" => "scale", "width" => 103));
    $this->api->transformation("api_test_transformation2");
    $this->api->delete_transformation("api_test_transformation2");
  }
  
  /**
   * @expectedException \Cloudinary\Api\NotFound
   */
  function test16b_transformation_delete() {
    $this->api->transformation("api_test_transformation2");
  }
  
  function test17a_transformation_delete_implicit() {
    // should allow deleting implicit transformation 
    $this->api->transformation("c_scale,w_100");
    $this->api->delete_transformation("c_scale,w_100");
  }
  
  /**
   * @expectedException \Cloudinary\Api\NotFound
   */
  function test17b_transformation_delete_implicit() {  
    $this->api->transformation("c_scale,w_100");
  }
}
