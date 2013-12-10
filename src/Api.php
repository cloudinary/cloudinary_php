<?php


namespace Cloudinary\Api {
  class Error extends \Exception {}
  class NotFound extends Error {}
  class NotAllowed extends Error {}
  class AlreadyExists extends Error {}
  class RateLimited extends Error {}
  class BadRequest extends Error {}
  class GeneralError extends Error {}
  class AuthorizationRequired extends Error {} 
  class Response extends \ArrayObject {
    function __construct($response) {        
        parent::__construct(\Cloudinary\Api::parse_json_response($response));
        $this->rate_limit_reset_at = strtotime($response->headers["X-FeatureRateLimit-Reset"]);
        $this->rate_limit_allowed = intval($response->headers["X-FeatureRateLimit-Limit"]);
        $this->rate_limit_remaining = intval($response->headers["X-FeatureRateLimit-Remaining"]);
    }    
  }  
}  


namespace Cloudinary {


class Api {
  static $CLOUDINARY_API_ERROR_CLASSES = array(
    400 => "\Cloudinary\Api\BadRequest",
    401 => "\Cloudinary\Api\AuthorizationRequired",
    403 => "\Cloudinary\Api\NotAllowed",
    404 => "\Cloudinary\Api\NotFound",
    409 => "\Cloudinary\Api\AlreadyExists",
    420 => "\Cloudinary\Api\RateLimited",
    500 => "\Cloudinary\Api\GeneralError"
   );

  function ping($options=array()) {
    return $this->call_api("get", array("ping"), array(), $options);
  }

  function usage($options=array()) {
    return $this->call_api("get", array("usage"), array(), $options);
  }

  function resource_types($options=array()) {
    return $this->call_api("get", array("resources"), array(), $options);
  }

  function resources($options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $type = \Cloudinary::option_get($options, "type");
    $uri = array("resources", $resource_type);
    if ($type) array_push($uri, $type);
    return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results", "prefix", "tags")), $options);    
  }
  
  function resources_by_tag($tag, $options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $uri = array("resources", $resource_type, "tags", $tag);
    return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results", "tags")), $options);    
  }
  
  function resource($public_id, $options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $type = \Cloudinary::option_get($options, "type", "upload");
    $uri = array("resources", $resource_type, $type, $public_id);
    return $this->call_api("get", $uri, $this->only($options, array("exif", "colors", "faces", "image_metadata", "pages", "max_results")), $options);      
  }
  
  function delete_resources($public_ids, $options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $type = \Cloudinary::option_get($options, "type", "upload");
    $uri = array("resources", $resource_type, $type);
    return $this->call_api("delete", $uri, array_merge(array("public_ids"=>$public_ids), $this->only($options, array("keep_original"))), $options);      
  }

  function delete_resources_by_prefix($prefix, $options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $type = \Cloudinary::option_get($options, "type", "upload");
    $uri = array("resources", $resource_type, $type);
    return $this->call_api("delete", $uri, array_merge(array("prefix"=>$prefix), $this->only($options, array("keep_original", "next_cursor"))), $options);      
  }
  
  function delete_all_resources($options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $type = \Cloudinary::option_get($options, "type", "upload");
    $uri = array("resources", $resource_type, $type);
    return $this->call_api("delete", $uri, array_merge(array("all"=>True), $this->only($options, array("keep_original", "next_cursor"))), $options);      
  }  
  
  function delete_resources_by_tag($tag, $options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $uri = array("resources", $resource_type, "tags", $tag);
    return $this->call_api("delete", $uri, $this->only($options, array("keep_original", "next_cursor")), $options);    
  }
  
    function delete_derived_resources($derived_resource_ids, $options=array()) {
    $uri = array("derived_resources");
    return $this->call_api("delete", $uri, array("derived_resource_ids"=>$derived_resource_ids), $options);      
  }

  function tags($options=array()) {
    $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
    $uri = array("tags", $resource_type);
    return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results", "prefix")), $options);
  }
  
  function transformations($options=array()) {
    return $this->call_api("get", array("transformations"), $this->only($options, array("next_cursor", "max_results")), $options);    
  }
  
  function transformation($transformation, $options=array()) {
    $uri = array("transformations", $this->transformation_string($transformation));
    return $this->call_api("get", $uri, $this->only($options, array("max_results")), $options);    
  }
  
  function delete_transformation($transformation, $options=array()) {
    $uri = array("transformations", $this->transformation_string($transformation));
    return $this->call_api("delete", $uri, array(), $options);    
  }
    
  # updates - currently only supported update is the "allowed_for_strict" boolean flag
  function update_transformation($transformation, $updates=array(), $options=array()) {
    $uri = array("transformations", $this->transformation_string($transformation));
    $params = $this->only($updates, array("allowed_for_strict"));
    if (isset($updates["unsafe_update"])) {
      $params["unsafe_update"] = $this->transformation_string($updates["unsafe_update"]);  
    }
    return $this->call_api("put", $uri, $params, $options);    
  }
  
  function create_transformation($name, $definition, $options=array()) {
    $uri = array("transformations", $name);
    return $this->call_api("post", $uri, array("transformation"=>$this->transformation_string($definition)), $options);    
  }
  
  protected function call_api($method, $uri, $params, &$options) {
    $prefix = \Cloudinary::option_get($options, "upload_prefix", \Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com"));
    $cloud_name = \Cloudinary::option_get($options, "cloud_name", \Cloudinary::config_get("cloud_name"));
    if (!$cloud_name) throw new \InvalidArgumentException("Must supply cloud_name");
    $api_key = \Cloudinary::option_get($options, "api_key", \Cloudinary::config_get("api_key"));
    if (!$api_key) throw new \InvalidArgumentException("Must supply api_key");
    $api_secret = \Cloudinary::option_get($options, "api_secret", \Cloudinary::config_get("api_secret"));
    if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");
    $api_url = implode("/", array_merge(array($prefix, "v1_1", $cloud_name), $uri));
    $api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params)); 
    $ch = curl_init($api_url);    
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "{$api_key}:{$api_secret}");
    curl_setopt($ch, CURLOPT_CAINFO,realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."cacert.pem");
    $response = $this->execute($ch);       
    $curl_error = NULL;
    if(curl_errno($ch))
    {
        $curl_error = curl_error($ch);
    }
    curl_close($ch);
    if ($curl_error != NULL) {
        throw new \Cloudinary\Api\GeneralError("Error in sending request to server - " . $curl_error);
    }
    if ($response->responseCode == 200) {
      return new \Cloudinary\Api\Response($response);
    } else {
      $exception_class = \Cloudinary::option_get(self::$CLOUDINARY_API_ERROR_CLASSES, $response->responseCode);
      if (!$exception_class) throw new \Cloudinary\Api\GeneralError("Server returned unexpected status code - {$response->responseCode} - {$response->body}");
      $json = $this->parse_json_response($response);
      throw new $exception_class($json["error"]["message"]);
    }
  }
  
  # Based on http://snipplr.com/view/17242/
  protected function execute($ch) {
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
            if (isset($headers[$headername])) 
                $headers[$headername] .= ',' . $headervalue;
            else 
                $headers[$headername] = $headervalue;
        }
        if ($h === false) {
            $content .= $str."\n";
        }
        $str = strtok("\n");
    }
    $result = new \stdClass;    
    $result->headers = $headers;
    $result->body = trim($content);
    $result->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $result;
  }
  
  static function parse_json_response($response) {
    $result = json_decode($response->body, TRUE);
    if ($result == NULL) {
      $error = json_last_error();
      throw new \Cloudinary\Api\GeneralError("Error parsing server response ({$response->responseCode}) - {$response->body}. Got - {$error}");
    }
    return $result; 
  }
  
  protected function only(&$hash, $keys) {
    $result = array();
    foreach ($keys as $key) {
      if (isset($hash[$key])) $result[$key] = $hash[$key];
    }
    
    return $result;
  }
  
  protected function transformation_string($transformation) {
    return is_string($transformation) ? $transformation : \Cloudinary::generate_transformation_string($transformation);
  }
}

}
?>
