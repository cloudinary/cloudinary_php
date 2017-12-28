<?php
namespace Cloudinary {

    use PHPUnit\Framework\TestCase;
    const RAW_FILE = "tests/docx.docx";
    const TEST_IMG = "tests/logo.png";
    const TEST_ICO = "tests/favicon.ico";
    const LOGO_SIZE = 3381;
    define("SUFFIX", getenv("TRAVIS_JOB_ID") ?: rand(11111, 99999));
    define('TEST_TAG', 'cloudinary_php');
    define('UNIQUE_TEST_TAG', TEST_TAG . "_" . SUFFIX);

    // For compatibility with the new versions of phpunit
    if (!class_exists('\PHPUnit\Framework\TestCase') && class_exists('\PHPUnit_Framework_TestCase')) {
        /** @noinspection PhpUndefinedClassInspection */
        class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
    }

    /**
     * Class Curl
     * Allows mocking Curl operations in the tests
     *
     * @package Cloudinary
     */
    class Curl
    {
        /**
         * @var Curl|\PHPUnit_Framework_MockObject_MockObject
         * the instance used in the tests. Either the original Curl object or a stubbed version
         */
        public static $instance;

        /** @var array|null collects all the parameters of the curl request */
        public $parameters = null;

        /** @var array|null keeps the result of `curl_exec` */
        public $result = null;

        /** @var mixed */
        public $apiResponse;

        /** @var string */
        public $url;

        public function __construct()
        {
            $this->parameters = array();
            $this->apiResponse = <<<END
HTTP/1.1 100 Continue

HTTP/1.1 200 OK
Cache-Control: max-age=0, private, must-revalidate
Content-Type: application/json; charset=utf-8
Date: Wed, 01 Jun 2016 19:44:32 GMT
ETag: "ed198f38b655b997725facd2ad4a8341"
Server: cloudinary
Status: 200 OK
X-FeatureRateLimit-Limit: 5000
X-FeatureRateLimit-Remaining: 4931
X-FeatureRateLimit-Reset: Wed, 01 Jun 2016 20:00:00 GMT
X-Request-Id: ed5691d50f137e37
X-UA-Compatible: IE=Edge,chrome=1
Content-Length: 482
Connection: keep-alive

{"public_id":"oej8n7ezhwmk1fp1xqfd"}
END;
            $this->apiResponse = str_replace("\n", "\r\n", $this->apiResponse);
            $this->uploadResponse = <<<END
{
"public_id":"oej8n7ezhwmk1fp1xqfd"

}
END;
            $this->uploadResponse = str_replace("\n", "\r\n", $this->uploadResponse);
        }

        /**
         * @param TestCase $test
         */
        public static function mockApi(TestCase $test)
        {
            Curl::$instance = $test->getMockBuilder('\Cloudinary\Curl')
                ->setMethods(array("exec", "getinfo"))
                ->getMock();
            Curl::$instance->method("exec")->willReturn(Curl::$instance->apiResponse);
            Curl::$instance->method("getinfo")->willReturn(200);
        }

        /**
         * @param TestCase $test
         */
        public static function mockUpload(TestCase $test)
        {
            Curl::$instance = $test->getMockBuilder('\Cloudinary\Curl')
                ->setMethods(array("exec", "getinfo"))
                ->getMock();
            Curl::$instance->method("exec")
                ->will($test->returnValue(Curl::$instance->uploadResponse));
            Curl::$instance->method("getinfo")
                ->will($test->returnValue(200));
        }

        /**
         * @param $ch
         *
         * @return array|mixed|null
         */
        public function exec($ch)
        {
            $this->result = \curl_exec($ch);

            return $this->result;
        }

        /**
         * @param $ch
         * @param $option
         * @param $value
         *
         * @return bool
         */
        public function setopt($ch, $option, $value)
        {
            $this->parameters[$option] = $value;

            return $this->globalSetopt($ch, $option, $value);
        }

        /**
         * @param $ch
         * @param $option
         * @param $value
         *
         * @return bool
         */
        public function globalSetopt($ch, $option, $value)
        {
            return \curl_setopt($ch, $option, $value);
        }

        /**
         * When stubbing exec() this function must be stubbed too to return code
         *
         * @inheritdoc
         */
        public function getinfo($ch, $opt)
        {
            return \curl_getinfo($ch, $opt);
        }

        /**
         * @param null $url
         *
         * @return resource
         */
        public function init($url = null)
        {
            $this->url = $url;

            return \curl_init($url);
        }

        /**
         * Returns the option that was set in the curl object
         *
         * @param string $option the name of the option
         *
         * @return mixed the value of the option
         */
        public function getopt($option)
        {
            return $this->parameters[$option];
        }

        public function http_method()
        {
            if (array_key_exists(CURLOPT_CUSTOMREQUEST, $this->parameters)) {
                return Curl::$instance->getopt(CURLOPT_CUSTOMREQUEST);
            } else {
                $method_array = array_keys(array_intersect_key(
                    $this->parameters,
                    array("CURLOPT_POST" => 0, "CURLOPT_PUT" => 0, "CURLOPT_HTTPGET" => 0)
                ));
                if (count($method_array) > 0) {
                    return preg_replace("/CURLOPT_(HTTP)?/", "", $method_array[0]);
                } else {
                    return "POST";
                }
            }
        }

        public function url_path()
        {
            return parse_url($this->url, PHP_URL_PATH);
        }

        /**
         * Returns the POST fields that were meant to be sent to the server
         * @return array an array of field name => value
         */
        public function fields()
        {
            if ($this->http_method() == "GET") {
                parse_str(parse_url($this->url, PHP_URL_QUERY), $params);

                return $params;
            } else {
                return $this->parameters[CURLOPT_POSTFIELDS];
            }
        }

    }

// Override global curl functions

    function curl_init($url = null)
    {
        return Curl::$instance->init($url);
    }

    function curl_exec($ch)
    {
        $result = Curl::$instance->exec($ch);

        return $result;
    }

    function curl_setopt($ch, $option, $value)
    {
        return Curl::$instance->setopt($ch, $option, $value);
    }

    function curl_getinfo($ch, $opt)
    {
        return Curl::$instance->getinfo($ch, $opt);
    }

    /**
     * @param TestCase $test
     * @param $name
     * @param $expectedValue
     * @param string $message
     *
     * @throws \PHPUnit_Framework_Exception
     * @internal param $fields
     */
    function assertParam(TestCase $test, $name, $expectedValue = null, $message = '')
    {
        $fields = Curl::$instance->fields();
        if (strlen($message) == 0) {
            $message = "should support the '$name' parameter";
        }
        $test->assertArrayHasKey($name, $fields, $message);
        if ($expectedValue != null) {
            $test->assertEquals($expectedValue, $fields[$name]);
        }
    }

    /**
     * @param TestCase $test
     * @param mixed $actualValue
     * @param mixed $expectedValue
     * @param string $message
     */
    function assertJson(TestCase $test, $actualValue, $expectedValue = null, $message = '')
    {
        if (strlen($message) == 0) {
            $message = "should coorectly encode JSON parameters";
        }
        $test->assertJsonStringEqualsJsonString($actualValue, $expectedValue, $message);
    }

    /**
     * @param TestCase $test
     * @param string $name
     * @param string $message
     */
    function assertNoParam(TestCase $test, $name, $message = '')
    {
        $fields = Curl::$instance->fields();
        $test->assertArrayNotHasKey($name, $fields, $message);
    }

    /**
     * @param TestCase $test
     * @param string $message
     */
    function assertPost(TestCase $test, $message = "http method should be POST")
    {
        $test->assertEquals("POST", Curl::$instance->http_method(), $message);
    }

    /**
     * @param TestCase $test
     * @param string $message
     */
    function assertPut(TestCase $test, $message = "http method should be PUT")
    {
        $test->assertEquals("PUT", Curl::$instance->http_method(), $message);
    }

    /**
     * @param TestCase $test
     * @param string $message
     */
    function assertGet(TestCase $test, $message = "http method should be GET")
    {
        $test->assertEquals("GET", Curl::$instance->http_method(), $message);
    }

    /**
     * @param TestCase $test
     * @param string $message
     */
    function assertDelete(TestCase $test, $message = "http method should be DELETE")
    {
        $test->assertEquals("DELETE", Curl::$instance->http_method(), $message);
    }

    /**
     * @param TestCase $test
     * @param string $path
     * @param string $message
     */
    function assertUrl(TestCase $test, $path, $message = '')
    {
        $cloud_name = \Cloudinary::config_get("cloud_name");
        $test->assertEquals("/v1_1/" . $cloud_name . $path, Curl::$instance->url_path(), $message);
    }
}
