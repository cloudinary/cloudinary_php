<?php

namespace Cloudinary {

    use PHPUnit\Framework\TestCase;
    const RAW_FILE = "tests/docx.docx";
    const TEST_IMG = "tests/logo.png";
    const TEST_ICO = "tests/favicon.ico";
    const TEST_PRESET_NAME = 'test_preset';
    const LOGO_SIZE = 3381;
    define("SUFFIX", getenv("TRAVIS_JOB_ID") ?: rand(11111, 99999));
    define('TEST_TAG', 'cloudinary_php');
    define('UNIQUE_TEST_TAG', TEST_TAG . "_" . SUFFIX);
    define('UNIQUE_TEST_ID', UNIQUE_TEST_TAG);
    define('UNIQUE_TEST_SPRITE_TAG', UNIQUE_TEST_TAG . "_sprite");
    define('UNIQUE_TEST_FOLDER', UNIQUE_TEST_TAG . "_folder");

    /**
     * Class Curl
     * Allows mocking Curl operations in the tests
     *
     * @package Cloudinary
     */
    class Curl
    {
        /** @var  Curl the instance used in the tests. Either the original Curl object or a stubbed version */
        public static $instance;
        /** @var array|null collects all the parameters of the curl request */
        public $parameters = null;
        /** @var array|null keeps the result of `curl_exec` */
        public $result = null;
        public $apiResponse;
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

            $this->uploadResponse = '{"public_id":"oej8n7ezhwmk1fp1xqfd"}';
        }

        public static function mockApi($test)
        {
            self::mockRequest($test, Curl::$instance->apiResponse);
        }

        public static function mockUpload($test)
        {
            self::mockRequest($test, Curl::$instance->uploadResponse);
        }

        /**
         * @param \PHPUnit\Framework\TestCase $test Test case to mock
         * @param $mocked_response
         */
        public static function mockRequest($test, $mocked_response)
        {
            Curl::$instance = $test
                ->getMockBuilder("\\Cloudinary\\Curl")
                ->setMethods(array("exec", "getinfo"))
                ->getMock();

            Curl::$instance
                ->method("exec")
                ->will($test->returnValue($mocked_response));

            Curl::$instance
                ->method("getinfo")
                ->will($test->returnValue(200));
        }

        public function exec($ch)
        {
            $this->result = \curl_exec($ch);
            return $this->result;
        }

        public function setopt($ch, $option, $value)
        {
            $this->parameters[$option] = $value;
            return $this->globalSetopt($ch, $option, $value);
        }

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

        public function init($url = null)
        {
            $this->url = $url;
            return \curl_init($url);
        }

        /**
         * Returns the option that was set in the curl object
         * @param string $option the name of the option
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
                $method_array = array_keys(
                    array_intersect_key(
                        $this->parameters,
                        array("CURLOPT_POST" => 0, "CURLOPT_PUT" => 0, "CURLOPT_HTTPGET" => 0)
                    )
                );
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

    Curl::$instance = new Curl();

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
     * @param $test
     * @param $name
     * @param $expectedValue
     * @param string $message
     */
    function assertParam($test, $name, $expectedValue = null, $message = '')
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

    function assertJson($test, $actualValue, $expectedValue = null, $message = '')
    {
        if (strlen($message) == 0) {
            $message = "should coorectly encode JSON parameters";
        }
        $test->assertJsonStringEqualsJsonString($actualValue, $expectedValue, $message);
    }

    function assertNoParam($test, $name, $message = '')
    {
        $fields = Curl::$instance->fields();
        $test->assertArrayNotHasKey($name, $fields, $message);
    }

    function assertPost($test, $message = "http method should be POST")
    {
        $test->assertEquals("POST", Curl::$instance->http_method(), $message);
    }

    function assertPut($test, $message = "http method should be PUT")
    {
        $test->assertEquals("PUT", Curl::$instance->http_method(), $message);
    }

    function assertGet($test, $message = "http method should be GET")
    {
        $test->assertEquals("GET", Curl::$instance->http_method(), $message);
    }

    function assertDelete($test, $message = "http method should be DELETE")
    {
        $test->assertEquals("DELETE", Curl::$instance->http_method(), $message);
    }

    function assertUrl($test, $path, $message = '')
    {
        $cloud_name = \Cloudinary::config_get("cloud_name");
        $test->assertEquals("/v1_1/" . $cloud_name . $path, Curl::$instance->url_path(), $message);
    }

    function assertHasHeader($test, $header, $message = '')
    {
        $headers = Curl::$instance->getopt(CURLOPT_HTTPHEADER);
        $test->assertTrue(is_array($headers), $message);
        $names = array();
        foreach ($headers as $h) {
            $chunks = explode(":", $h);
            // header names are case-insensitive according to rfc7230
            $names[] = strtolower(trim($chunks[0]));
        }
        $test->assertContains(strtolower($header), $names, $message);
    }

    /**
     * Reports an error if the $haystack array does not contain the $needle array.
     *
     * @param TestCase $test
     * @param array $haystack
     * @param array $needle
     * @param string $message
     */
    function assertArrayContainsArray($test, $haystack, $needle, $message = '')
    {
        $message = empty($message) ? 'The $haystack array does not contain the $needle array' : $message;
        $result = array_filter($haystack, function ($item) use ($needle) {
            return $item == $needle;
        });

        $test->assertGreaterThanOrEqual(1, count($result), $message);
    }

    /**
     * Asserts that request fields are correctly encoded into the HTTP request
     *
     * @param TestCase $test
     * @param array $fields
     * @param string $message
     */
    function assertEncodedRequestFields(TestCase $test, $fields = array(), $message = '')
    {
        assertJson(
            $test,
            json_encode($fields),
            Curl::$instance->fields(),
            empty($message) ? 'Should correctly encode JSON into the HTTP request' : $message
        );
    }

    /**
     * Verifies that a resource is valid.
     *
     * @param \PHPUnit\Framework\TestCase $test .
     * @param array $resource An uploaded resource.
     * @param array $fields Optional array of fields the resource is expected to have.
     */
    function assertValidResource(TestCase $test, $resource, $expectedValues = array())
    {

        $incorrectTypeMessage = "Incorrect type for %s received %s a(n) %s, expected %s ";
        $valueNotInSetMessage = "Value %s not allowed for %s. Should be one of %s";
        $doesNotContainKeyMessage = "Array for %s does not contain key %s";
        $doesNotContainExpectedElementMessage = "Resource does not contain expected key %s";
        $doesNotContainExpectedValueMessage = "%s does not contain expected value %s";
        $doesNotMatchExpectedValueMessage = "Resource value for key %s does not match the expected value";

        /* Common fields a resource always returns (either from upload call or from resources call (for image, video, pdf file, zip etc.) */
        $commonFields = array(
            'public_id',
            'version',
            'resource_type',
            'type',
            'created_at',
            'bytes',
            'url',
            'secure_url'
        );

        foreach($commonFields as $field){
            $test->assertArrayHasKey("$field", $resource, sprintf($doesNotContainKeyMessage, 'resource', $field));
        }

        /* Verify types of fields if the resource does include them. */
        $types = array(
            'public_id'         => 'string',
            'version'           => 'int',
            'resource_type'     => 'string',
            'type'              => 'string',
            'created_at'        => 'string',
            'bytes'             => 'int',
            'url'               => 'string',
            'secure_url'        => 'string',
            'format'            => 'string',
            'width'             => 'int',
            'height'            => 'int',
            'signature'         => 'string',
            'etag'              => 'string',
            'placeholder'       => 'boolean',
            'original_filename' => 'string',
            'access_mode'       => 'string',
        );

        foreach($types as $name => $type) {
            if (array_key_exists($name, $resource)) {

                $test->assertInternalType($type, $resource[$name], sprintf($incorrectTypeMessage, $name, $resource[$name], gettype($resource[$name]), $type));

                if ($name == 'access_mode') {
                    $test->assertContains($resource['access_mode'], array('public', 'authenticated'), sprintf($valueNotInSetMessage, $resource['access_mode'], 'access_mode', 'public, authenticated'));
                }
            }
        }


        /* Check a few specific elements that are arrays
           Tags: array of strings
           Access Control: array of arrays, each subarray should have the field 'access_type' with a value of 'token' or 'anonymous'
           Eager: array of arrays, each subarray should have the fields: transformation, width, height, url, secure_url
        */

        /*tags*/
        if (array_key_exists('tags', $resource)){
            $test->assertTrue(is_array($resource['tags']), sprintf($incorrectTypeMessage, 'tags', '', gettype($resource['tags']), 'array'));
            foreach($resource['tags'] as $tag){
                $test->assertInternalType('string', $tag, sprintf($incorrectTypeMessage, 'tag', $tag, gettype($tag), 'string'));
            }
        }

        /* access_control*/
        if (array_key_exists('access_control', $resource)){
            $test->assertTrue(is_array($resource['access_control']), sprintf($incorrectTypeMessage, 'access_control', '', gettype($resource['access_control']), 'array'));
            foreach($resource['access_control'] as $accessControlDetails){
                $test->assertTrue(is_array($accessControlDetails), sprintf($incorrectTypeMessage, 'access_control item', '', gettype($accessControlDetails), 'array'));
                $test->assertArrayHasKey('access_type', $accessControlDetails, sprintf($doesNotContainKeyMessage, 'access_control', 'access_type'));
                foreach($accessControlDetails as $k=>$v){
                    $test->assertContains($k, array('access_type', 'start', 'end'), sprintf($valueNotInSetMessage, $k, 'access_control item', 'access_type, start, end'));
                    $test->assertInternalType('string', $v, sprintf($incorrectTypeMessage, 'access_control item', $v, gettype($v), 'string'));
                    if ($k == 'access_type'){
                        $test->assertContains($v, array('token', 'anonymous'), sprintf($valueNotInSetMessage, $v, 'access_type', 'token, anonymous'));
                    }
                    unset($k); //unset in case there is an element without a key
                }
            }

        }

        /*eager*/
        if (array_key_exists('eager', $resource)){
            $test->assertTrue(is_array($resource['eager']), sprintf($incorrectTypeMessage, 'eager', '', gettype($resource['eager']), 'array'));
            foreach($resource['eager'] as $eagerDetails){
                $test->assertTrue(is_array($eagerDetails), sprintf($incorrectTypeMessage, 'eager details', '', gettype($eagerDetails), 'array'));
                $eagerFieldsTypes = array('transformation' => 'string',
                                               'width'          => 'int',
                                               'height'         => 'int',
                                               'url'            => 'string',
                                               'secure_url'     => 'string');

                foreach($eagerFieldsTypes as $name=>$type){
                    $test->assertArrayHasKey($name, $eagerDetails, sprintf($doesNotContainKeyMessage, 'eager details', $name));

                    $test->assertInternalType($type, $eagerDetails[$name], sprintf($incorrectTypeMessage, "eager details $name", $eagerDetails[$name], gettype($eagerDetails[$name]), $type));
                }
            }
        }

        /* Check expected values passed in match what the resource returns */

        $test->assertTrue(is_array($expectedValues));

        foreach ($expectedValues as $eField => $eValue) {
            $test->assertArrayHasKey($eField, $resource, sprintf($doesNotContainExpectedElementMessage, $eField));

            if ($eField == 'tags'){
                $expectedTagValues = $expectedValues['tags'];
                foreach($expectedTagValues as $expectedTag){
                    $test->assertContains($expectedTag, $resource['tags'], sprintf($doesNotContainExpectedValueMessage, 'tags', $expectedTag));
                }
            }
            elseif($eField == 'access_control'){
                $expectedACValues = $expectedValues['access_control'];
                foreach($expectedACValues as $expectedACItem){
                    $foundItem = false;
                    foreach($resource['access_control'] as $resourceACItem){
                        $diff = array_diff_assoc($expectedACItem, $resourceACItem);
                        if (empty($diff)){
                            $foundItem = true;
                            continue 2;
                        }
                    }
                    $test->assertEquals($foundItem, true, sprintf($doesNotMatchExpectedValueMessage, $eField));
                }
            }elseif($eField == 'eager'){
                $expectedEValues = $expectedValues['eager'];
                foreach($expectedEValues as $expectedEItem){
                    $foundItem = false;
                    foreach($resource['eager'] as $resourceEItem){
                        $diff = array_diff_assoc($expectedEItem, $resourceEItem);
                        if (empty($diff)){
                            $foundItem = true;
                            continue 2;
                        }
                    }
                    $test->assertEquals($foundItem, true, sprintf($doesNotMatchExpectedValueMessage, $eField));
                }
            }else{
                $test->assertEquals($eValue, $resource[$eField], sprintf($doesNotMatchExpectedValueMessage, $eField));
            }

        }

    }

}


