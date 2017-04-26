<?php
namespace Cloudinary {
  $base = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..");
  require_once(join(DIRECTORY_SEPARATOR, array($base, "src", "Cloudinary.php")));
  require_once(join(DIRECTORY_SEPARATOR, array($base, "src", "Uploader.php")));
  require_once(join(DIRECTORY_SEPARATOR, array($base, "src", "Api.php")));
  require_once(join(DIRECTORY_SEPARATOR, array($base, "src", "Search.php")));
  require_once("TestHelper.php");
  use PHPUnit_Framework_TestCase;

  class SearchTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
      Curl::$instance = new Curl();
    }

    public function setUp() {
          \Cloudinary::reset_config();
          if (!\Cloudinary::config_get("api_secret")) {
            $this->markTestSkipped("Please setup environment for Search test to run");
          }
          $this->search = new \Cloudinary\Search();      
          $this->tag = "php_test_" . rand(11111, 99999);
      }

    public function tearDown() {
          Curl::$instance = new Curl();
          $api = new \Cloudinary\Api();
          $api->delete_resources_by_tag($this->tag);
    }

      public function test_empty_query() {
        $result = $this->search->to_query();
        $this->assertEmpty($result, "Should generate an empty query JSON");
      }

      public function test_query_with_params() {

          $result = $this->search->expression("format:jpg")->max_results(10)->next_cursor("abcd")->sort_by("created_at", "asc")->sort_by("updated_at")->aggregate("format")->aggregate("resource_type")->with_field("tags")->with_field("image_metadata")->to_query();

          $this->assertArrayHasKey("expression", $result, "Expression should be present in generated query");
          $this->assertEquals("format:jpg", $result["expression"], "Expression should be consistent");
          $this->assertArrayHasKey("max_results", $result, "Max-results should be present in generated query");
          $this->assertEquals("10", $result["max_results"], "Max-results should be consistent");
          $this->assertArrayHasKey("next_cursor", $result, "Next-cursor should be present in generated query");
          $this->assertEquals("abcd", $result["next_cursor"], "Next-cursor should be consistent");
          $this->assertArrayHasKey("sort_by", $result, "Sort-by should be present in generated query");
          $this->assertEquals(array(array("created_at" => "asc"), array("updated_at" => "desc")), $result["sort_by"], "Sort-by should be consistent");
          $this->assertArrayHasKey("aggregate", $result, "Aggregate should be present in generated query");
          $this->assertEquals(array("format","resource_type"), $result["aggregate"], "Aggregate should be consistent");
          $this->assertArrayHasKey("with_field", $result, "With_field should be present in generated query");
          $this->assertEquals(array("tags","image_metadata"), $result["with_field"], "With_field should be consistent");
      }

      public function test_integration() {
        Uploader::upload("tests/logo.png", array("tags"=>array($this->tag), "public_id"=>"sampleImage1"));
        Uploader::upload("tests/logo.png", array("tags"=>array($this->tag), "public_id"=>"sampleImage2"));
        Uploader::upload("tests/logo.png", array("tags"=>array($this->tag), "public_id"=>"sampleRaw1", "resource_type"=>"raw"));
        sleep(4); # indexing is done asynchronously 

        $result = $this->search->expression("tags:".$this->tag)->aggregate("resource_type")->execute();
        $this->assertEquals($result["total_count"], 3, "should list 3 matching resources");
        $this->assertEquals($result["aggregations"]["resource_type"]["image"], 2, "should list 2 resources of type image");
      }

      public function test_execute_with_params() {
        Curl::mockApi($this);
        $result = $this->search->expression("format:jpg")->max_results(10)->next_cursor("abcd")->sort_by("created_at", "asc")->sort_by("updated_at")->aggregate("format")->aggregate("resource_type")->with_field("tags")->with_field("image_metadata")->execute();
        
        assertJson($this, json_encode(array("sort_by" => array(array("created_at" => "asc"),array("updated_at" => "desc")), "aggregate" => array("format", "resource_type"), "with_field" => array("tags", "image_metadata"), "expression" => "format:jpg", "max_results" => 10, "next_cursor" => "abcd")), Curl::$instance->fields(), "Should correctly encode JSON into the HTTP request");
        
        assertJson($this, json_encode(array("Content-type: application/json", "Accept: application/json")), json_encode(Curl::$instance->getopt(CURLOPT_HTTPHEADER)), "Should use right headers for execution of advanced search api");
      }
  }
}
?>