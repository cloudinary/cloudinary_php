<?php

class Cloudinary {
    private static $config = NULL;
    const SHARED_CDN = "d3jpl91pxevbkh.cloudfront.net";
    public static $JS_CONFIG_PARAMS = array("api_key", "cloud_name", "private_cdn", "secure_distribution", "cdn_subdomain");

    public static function config($values = NULL) {
        if (self::$config == NULL) {
            self::reset_config();
        }
        if ($values != NULL) {
            self::$config = array_merge(self::$config, $values);
        }
        return self::$config;
    }

    public static function reset_config() {
        self::$config = array();
        if (getenv("CLOUDINARY_URL")) {
            $uri = parse_url(getenv("CLOUDINARY_URL"));
            $config = array("cloud_name" => $uri["host"],
                            "api_key" => $uri["user"],
                            "api_secret" => $uri["pass"],
                            "private_cdn" => isset($uri["path"]));
            if (isset($uri["path"])) {
                $config["secure_distribution"] = substr($uri["path"], 1);
            } 
            self::$config = array_merge(self::$config, $config);
        }
    }

    public static function config_get($option, $default=NULL) {
        return Cloudinary::option_get(self::$config, $option, $default);
    }

    public static function option_get(&$options, $option, $default=NULL) {
        if (isset($options[$option])) { 
            return $options[$option];
        } else {
            return $default;
        }
    }
    
    public static function option_consume(&$options, $option, $default=NULL) {
        if (isset($options[$option])) { 
            $value = $options[$option];
            unset($options[$option]);
            return $value;
        } else {
            unset($options[$option]);
            return $default;
        }
    }
    
    public static function build_array($value) {
        if (is_array($value) && $value == array_values($value)) {
            return $value;
        } else if ($value == NULL) {
            return array();
        } else {
            return array($value);
        }
    }

    // Warning: $options are being destructively updated!
    public static function generate_transformation_string(&$options=array()) {
        $size = Cloudinary::option_consume($options, "size");
        if ($size) list($options["width"], $options["height"]) = preg_split("/x/", $size);

        $width = Cloudinary::option_get($options, "width");
        $height = Cloudinary::option_get($options, "height");

        if ($width && floatval($width) < 1) unset($options["width"]); 
        if ($height && floatval($height) < 1) unset($options["height"]); 
         
        $crop = Cloudinary::option_consume($options, "crop");
        if (!$crop) $width = $height = NULL;

        $background = Cloudinary::option_consume($options, "background");
        if ($background) $background = preg_replace("/^#/", 'rgb:', $background);
                
        $base_transformations = Cloudinary::build_array(Cloudinary::option_consume($options, "transformation"));
        if (count(array_filter($base_transformations, "is_array")) > 0) {
            $callback = function($base_transformation) {
                $base_transformation_copy = $base_transformation;
                return is_array($base_transformation) ? 
                       Cloudinary::generate_transformation_string($base_transformation_copy) : 
                       Cloudinary::generate_transformation_string(array("transformation"=>$base_transformation));
            };
            $base_transformations = array_map($callback, $base_transformations);
            $named_transformation = "";
        } else {
            $named_transformation = implode(".", $base_transformations);
            $base_transformations = array();
        }
        
        $effect = Cloudinary::option_consume($options, "effect");
        if (is_array($effect)) $effect = implode(":", $effect);

        $params = array("w"=>$width, "h"=>$height, "t"=>$named_transformation, "c"=>$crop, "b"=>$background, "e"=>$effect);
        $simple_params = array("x"=>"x", "y"=>"y", "r"=>"radius", "d"=>"default_image", "g"=>"gravity", "q"=>"quality", "p"=>"prefix", "a"=>"angle", "l"=>"overlay", "f"=>"fetch_format");
        foreach ($simple_params as $param=>$option) { 
            $params[$param] = Cloudinary::option_consume($options, $option);
        }        

        $params = array_filter($params);
        ksort($params);
        $join_pair = function($key, $value) { return $key . "_" . $value; };
        $transformation = implode(",", array_map($join_pair, array_keys($params), array_values($params)));
        $raw_transformation = Cloudinary::option_consume($options, "raw_transformation");
        $transformation = implode(",", array_filter(array($transformation, $raw_transformation)));
        array_push($base_transformations, $transformation);
        return implode("/", array_filter($base_transformations));
    }

    public static function hash_join($sep, &$array) {
    } 

    // Warning: $options are being destructively updated!
    public static function cloudinary_url($source, &$options=array()) {
        $type = Cloudinary::option_consume($options, "type");

        if ($type == "fetch" && !isset($options["fetch_format"])) {
            $options["fetch_format"] = Cloudinary::option_consume($options, "format");
        }
        $transformation = Cloudinary::generate_transformation_string($options);

        $resource_type = Cloudinary::option_consume($options, "resource_type", "image");
        $version = Cloudinary::option_consume($options, "version");
        $format = Cloudinary::option_consume($options, "format");
        
        $cloud_name = Cloudinary::option_consume($options, "cloud_name", Cloudinary::config_get("cloud_name"));
        if (!$cloud_name) throw new InvalidArgumentException("Must supply cloud_name in tag or in configuration");
        $secure = Cloudinary::option_consume($options, "secure", Cloudinary::config_get("secure"));
        $private_cdn = Cloudinary::option_consume($options, "private_cdn", Cloudinary::config_get("private_cdn"));
        $secure_distribution = Cloudinary::option_consume($options, "secure_distribution", Cloudinary::config_get("secure_distribution"));
        $cdn_subdomain = Cloudinary::option_consume($options, "cdn_subdomain", Cloudinary::config_get("cdn_subdomain"));
        
        $original_source = $source;
        if (!$source) return $original_source;
        if ((!$type || $type == "asset") && preg_match("/^https?:\//i", $source)) return $original_source;
        if (!$type) $type = "upload";
                
        if ($format && $type != "fetch") $source = $source . "." . $format;
        if ($type == "fetch" || $type == "asset") $source = Cloudinary::smart_escape($source);
            
        if ($secure && !$secure_distribution) {
            if ($private_cdn) {
                throw new InvalidArgumentException("secure_distribution not defined");
            } else {
                $secure_distribution = Cloudinary::SHARED_CDN;
            }
        } 
        if ($secure) {
            $prefix = "https://" . $secure_distribution;
        } else {
            $subdomain = $cdn_subdomain ? "a" . ((crc32(source) % 5 + 5) % 5 + 1) : "";
            $prefix = "http://" . $subdomain . ($private_cdn ? $cloud_name . "-" : "") . "res.cloudinary.com";
        }
        if (!$private_cdn) $prefix .= "/" . $cloud_name; 
                
        return preg_replace("/([^:])\/+/", "$1/", implode("/", array($prefix, $resource_type,
         $type, $transformation, $version ? "v" . $version : "", $source)));
    }

    // Based on http://stackoverflow.com/a/1734255/526985
    private static function smart_escape($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')', '%3A'=>':', '%2F'=>'/');
        return strtr(rawurlencode($str), $revert);
    }

    public static function cloudinary_api_url($action = 'upload', $options = array()) {
        $cloudinary = Cloudinary::option_get($options, "upload_prefix", Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com"));
        $cloud_name = Cloudinary::config_get("cloud_name");
        if (!$cloud_name) throw new InvalidArgumentException("Must supply cloud_name in tag or in configuration");
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        return implode("/", array($cloudinary, "v1_1", $cloud_name, $resource_type, $action));
    }

    public static function random_public_id() {
        return substr(sha1(uniqid(Cloudinary::config_get("api_secret", "") . mt_rand())), 0, 16);
    }

    public static function signed_preloaded_image($result) {
        return $result["resource_type"] . "/upload/v" . $result["version"] . "/" . $result["public_id"] .
               (isset($result["format"]) ? "." . $result["format"] : "") . "#" . $result["signature"];
    }

    public static function api_sign_request($params_to_sign, $api_secret) {
        $params = array();
        foreach ($params_to_sign as $param => $value) {
            if ($value) {
                $params[$param] = is_array($value) ? implode(",", $value) : $value;
            }
        }
        ksort($params);
        $join_pair = function($key, $value) { return $key . "=" . $value; };
        $to_sign = implode("&", array_map($join_pair, array_keys($params), array_values($params)));
        return sha1($to_sign . $api_secret);
    }
    public static function html_attrs($options) {
        ksort($options);
        $join_pair = function($key, $value) { return $key . "='" . $value . "'"; };
        return implode(" ", array_map($join_pair, array_keys($options), array_values($options)));
    }
}


// Examples
// cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello") # W/H are not sent to cloudinary
// cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello", "crop"=>"fit") # W/H are sent to cloudinary
function cl_image_tag($source, $options = array()) {
    $source = cloudinary_url_internal($source, $options);
    if (isset($options["html_width"])) $options["width"] = Cloudinary::option_consume($options, "html_width");
    if (isset($options["html_height"])) $options["height"] = Cloudinary::option_consume($options, "html_height");

    return "<img src='" . $source . "' " . Cloudinary::html_attrs($options) . "/>";
}

function fetch_image_tag($url, $options = array()) {
    $options["type"] = "fetch";
    return cl_image_tag($url, $options);
}
  
function facebook_profile_image_tag($profile, $options = array()) {
    $options["type"] = "facebook";
    return cl_image_tag($profile, $options);
}

function gravatar_profile_image_tag($email, $options = array()) {
    $options["type"] = "gravatar";
    $options["format"] = "jpg";
    return cl_image_tag(md5(strtolower(trim($email))), $options);
}

function twitter_profile_image_tag($profile, $options = array()) {
    $options["type"] = "twitter";
    return cl_image_tag($profile, $options);
}

function twitter_name_profile_image_tag($profile, $options = array()) {
    $options["type"] = "twitter_name";
    return cl_image_tag($profile, $options);
}

function cloudinary_js_config() {
    $params = array();
    foreach (Cloudinary::JS_CONFIG_PARAMS as $param) {
        $value = Cloudinary::config_get($param);
        if ($value) $params[$param] = $value;
    }
    return "<script type='text/javascript'>\n" .
        "$.cloudinary.config(" . json_encode($params) . ");\n" .
        "</script>\n";
}

function cloudinary_url($source, $options = array()) {
    return cloudinary_url_internal($source, $options);
}
function cloudinary_url_internal($source, &$options = array()) {
    if (!isset($options["secure"])) $options["secure"] = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' );

    return Cloudinary::cloudinary_url($source, $options);
}
  
?>
