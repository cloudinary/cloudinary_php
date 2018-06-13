<?php
namespace Cloudinary;

/**
 * Class HttpClient
 * @package Cloudinary
 */
class HttpClient
{
    const DEFAULT_HTTP_TIMEOUT = 60;

    private $timeout;

    /**
     * HttpClient constructor.
     *
     * @param $options
     */
    public function __construct($options = null)
    {
        $this->timeout = \Cloudinary::option_get($options, "timeout", self::DEFAULT_HTTP_TIMEOUT);
    }

    /**
     * @param $url
     *
     * @return \ArrayObject
     *
     * @throws Error
     */
    public function get_json($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, \Cloudinary::userAgent());

        $response = $this->execute($ch);

        $curl_error = null;
        if (curl_errno($ch)) {
            $curl_error = curl_error($ch);
        }

        curl_close($ch);

        if ($curl_error != null) {
            throw new Error("Error in sending request to server - " . $curl_error);
        }

        if ($response->responseCode != 200) {
            $exception_class = \Cloudinary::option_get(
                Api::$CLOUDINARY_API_ERROR_CLASSES,
                $response->responseCode
            );

            if (!$exception_class) {
                throw new \Cloudinary\Error(
                    "Server returned unexpected status code - {$response->responseCode} - {$response->body}"
                );
            }

            $json = self::parse_json_response($response);

            throw new $exception_class($json["error"]["message"]);
        }

        return self::parse_json_response($response);
    }

    /**
     * Executes HTTP request, parses response headers, leaves body as a string
     *
     * Based on http://snipplr.com/view/17242/
     *
     * @param resource $ch cURL handle
     *
     * @return \stdClass Containing headers, body, responseCode properties
     */
    protected static function execute($ch)
    {
        $string = curl_exec($ch);
        $headers = array();
        $content = '';
        $str = strtok($string, "\n");
        $h = null;
        while ($str !== false) {
            if ($h and trim($str) === '') {
                $h = false;
                continue;
            }
            if ($h !== false and false !== strpos($str, ':')) {
                $h = true;
                list($headername, $headervalue) = explode(':', trim($str), 2);
                $headervalue = ltrim($headervalue);
                if (isset($headers[$headername])) {
                    $headers[$headername] .= ',' . $headervalue;
                } else {
                    $headers[$headername] = $headervalue;
                }
            }
            if ($h === false) {
                $content .= $str . "\n";
            }
            $str = strtok("\n");
        }
        $result = new \stdClass;
        $result->headers = $headers;
        $result->body = trim($content);
        $result->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $result;
    }

    /**
     * Parses JSON string from response body.
     *
     * @param \stdClass $response Class representing response
     *
     * @return mixed Decoded JSON object
     *
     * @throws Error
     */
    public static function parse_json_response($response)
    {
        $result = json_decode($response->body, true);
        if ($result == null) {
            $error = json_last_error();
            throw new Error(
                "Error parsing server response ({$response->responseCode}) - {$response->body}. Got - {$error}"
            );
        }

        return $result;
    }
}
