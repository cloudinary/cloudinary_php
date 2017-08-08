<?php
namespace Cloudinary {

    $base = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
    require_once(join(DIRECTORY_SEPARATOR, array($base, 'src', 'Cloudinary.php')));
    require_once(join(DIRECTORY_SEPARATOR, array($base, 'src', 'Uploader.php')));
    require_once(join(DIRECTORY_SEPARATOR, array($base, 'src', 'Api.php')));
    require_once('TestHelper.php');
    use PHPUnit\Framework\TestCase;

    class ArchiveTest extends TestCase
    {
        /** @var string */
        protected $tag;

        public static function setUpBeforeClass()
        {
            Curl::$instance = new Curl();
        }

        public function setUp()
        {
            \Cloudinary::reset_config();
            if (!\Cloudinary::config_get("api_secret")) {
                $this->markTestSkipped('Please setup environment for Upload test to run');
            }
            $this->tag = sprintf('php_test_%s', getenv("TRAVIS_JOB_ID") ?: uniqid('', true));

            Uploader::upload("tests/logo.png", array("tags" => array($this->tag)));
            Uploader::upload("tests/logo.png", array("tags" => array($this->tag), "width" => 10, "crop" => "scale"));
        }

        public function tearDown()
        {
            Curl::$instance = new Curl();
            $api = new \Cloudinary\Api();
            $api->delete_resources_by_tag($this->tag);
        }

        public function test_create_zip()
        {
            $result = Uploader::create_zip(array("tags" => $this->tag));
            $this->assertEquals(2, $result["file_count"]);
        }

        public function testOptionalParams()
        {
            Uploader::create_zip(array(
                    "tags" => $this->tag,
                    "expires_at" => time() + 3600,
                    "skip_transformation_name" => true,
                    "allow_missing" => true
                )
            );
            assertUrl($this, '/image/generate_archive');
            assertParam($this, "target_format", Uploader::TARGET_FORMAT_ZIP);
            assertParam($this, "tags[0]", $this->tag);
            assertParam($this, "expires_at", null, "should support the 'expires_at' parameter");
            assertParam(
                $this,
                "skip_transformation_name",
                1,
                "should support the 'skip_transformation_name' parameter"
            );
            assertParam($this, "allow_missing", 1, "should support the 'allow_missing' parameter");
        }

        public function test_download_zip_url()
        {
            $result = \Cloudinary::download_zip_url(array("tags" => $this->tag));
            $file = tempnam(".", "zip");
            file_put_contents($file, file_get_contents($result));
            $zip = new \ZipArchive();
            $zip->open($file);
            $this->assertEquals(2, $zip->numFiles);
            $zip->close();
            unlink($file);
        }
    }
}
