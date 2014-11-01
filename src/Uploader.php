<?php
namespace Cloudinary {

    class Error extends \Exception {};  

    class Uploader {
        public static function build_upload_params(&$options)
        {
            $params = array("timestamp" => time(),
                "transformation" => \Cloudinary::generate_transformation_string($options),
                "public_id" => \Cloudinary::option_get($options, "public_id"),
                "callback" => \Cloudinary::option_get($options, "callback"),
                "format" => \Cloudinary::option_get($options, "format"),
                "backup" => \Cloudinary::option_get($options, "backup"),
                "faces" => \Cloudinary::option_get($options, "faces"),
                "image_metadata" => \Cloudinary::option_get($options, "image_metadata"),
                "exif" => \Cloudinary::option_get($options, "exif"),
                "colors" => \Cloudinary::option_get($options, "colors"),
                "type" => \Cloudinary::option_get($options, "type"),
                "eager" => Uploader::build_eager(\Cloudinary::option_get($options, "eager")),
                "headers" => Uploader::build_custom_headers(\Cloudinary::option_get($options, "headers")),
                "use_filename" => \Cloudinary::option_get($options, "use_filename"),
                "unique_filename" => \Cloudinary::option_get($options, "unique_filename"),
                "discard_original_filename" => \Cloudinary::option_get($options, "discard_original_filename"),
                "notification_url" => \Cloudinary::option_get($options, "notification_url"),
                "eager_notification_url" => \Cloudinary::option_get($options, "eager_notification_url"),
                "eager_async" => \Cloudinary::option_get($options, "eager_async"),
                "invalidate" => \Cloudinary::option_get($options, "invalidate"),
                "proxy" => \Cloudinary::option_get($options, "proxy"),
                "folder" => \Cloudinary::option_get($options, "folder"),
                "tags" => implode(",", \Cloudinary::build_array(\Cloudinary::option_get($options, "tags"))),
                "context" => \Cloudinary::encode_assoc_array(\Cloudinary::option_get($options, "context")),
                "face_coordinates" => \Cloudinary::encode_double_array(\Cloudinary::option_get($options, "face_coordinates")),
                "custom_coordinates" => \Cloudinary::encode_double_array(\Cloudinary::option_get($options, "custom_coordinates")),
                "moderation" => \Cloudinary::option_get($options, "moderation"),
                "raw_convert" => \Cloudinary::option_get($options, "raw_convert"),
                "ocr" => \Cloudinary::option_get($options, "ocr"),
                "categorization" => \Cloudinary::option_get($options, "categorization"),
                "detection" => \Cloudinary::option_get($options, "detection"),
                "similarity_search" => \Cloudinary::option_get($options, "similarity_search"),
                "auto_tagging" => \Cloudinary::option_get($options, "auto_tagging"),
                "background_removal" => \Cloudinary::option_get($options, "background_removal"),
                "phash" => \Cloudinary::option_get($options, "phash"),
                "return_delete_token" => \Cloudinary::option_get($options, "return_delete_token"),
                "upload_preset" => \Cloudinary::option_get($options, "upload_preset"),
                "allowed_formats" => \Cloudinary::encode_array(\Cloudinary::option_get($options, "allowed_formats")));
            array_walk($params, function (&$value, $key){ $value = (is_bool($value) ? ($value ? "1" : "0") : $value);});
            return array_filter($params,function($v){ return !is_null($v) && ($v !== "" );});
        }

        public static function unsigned_upload($file, $upload_preset, $options = array())
        {
            return Uploader::upload($file, array_merge($options, array("unsigned"=>TRUE, "upload_preset"=>$upload_preset)));
        }

        public static function upload($file, $options = array())
        {
            $params = Uploader::build_upload_params($options);
            return Uploader::call_api("upload", $params, $options, $file);
        }

        // Upload large raw files. Note that public_id should include an extension for best results.
        public static function upload_large($file, $options=array())
        {
            $src = fopen($file, 'r');
            $temp_file_name = tempnam(sys_get_temp_dir(), 'cldupload.' + pathinfo($file, PATHINFO_EXTENSION));
            $upload = $upload_id = NULL;
            $public_id = \Cloudinary::option_get($upload, "public_id");
            $index = 1;
            while (!feof($src)) {
                $dest = fopen($temp_file_name, 'w');
                stream_copy_to_stream($src, $dest, 20000000);
                fclose($dest);
                try {
                    $upload = Uploader::upload_large_part($temp_file_name, array_merge($options, 
                                array("public_id"=>$public_id, "upload_id"=>$upload_id, "part_number"=>$index, "final"=>feof($src))));
                } catch(\Exception $e) {
                    unlink($temp_file_name);
                    fclose($src);
                    throw $e;                    
                }
                $upload_id = \Cloudinary::option_get($upload, "upload_id");
                $public_id = \Cloudinary::option_get($upload, "public_id");
                $index += 1;
            }
            unlink($temp_file_name);
            fclose($src);
            return $upload;
        }
    

        // Upload large raw files. Note that public_id should include an extension for best results.
        public static function upload_large_part($file, $options=array())
        {
            $params = array(
                "timestamp" => time(),
                "type" => \Cloudinary::option_get($options, "type"),
                "backup" => \Cloudinary::option_get($options, "backup"),
                "final" => \Cloudinary::option_get($options, "final"),
                "part_number" => \Cloudinary::option_get($options, "part_number"),
                "upload_id" => \Cloudinary::option_get($options, "upload_id"),
                "public_id" => \Cloudinary::option_get($options, "public_id")
            );
            return Uploader::call_api("upload_large", $params, array_merge($options, array("resource_type" => "raw")), $file);
        }

        public static function destroy($public_id, $options = array())
        {
            $params = array(
                "timestamp" => time(),
                "type" => \Cloudinary::option_get($options, "type"),
                "invalidate" => \Cloudinary::option_get($options, "invalidate"),
                "public_id" => $public_id
            );
            return Uploader::call_api("destroy", $params, $options);
        }

        public static function rename($from_public_id, $to_public_id, $options = array())
        {
            $params = array(
                "timestamp" => time(),
                "type" => \Cloudinary::option_get($options, "type"),
                "from_public_id" => $from_public_id,
                "to_public_id" => $to_public_id,
                "overwrite" => \Cloudinary::option_get($options, "overwrite")
            );
            return Uploader::call_api("rename", $params, $options);
        }
        
        public static function explicit($public_id, $options = array())
        {
            $params = array(
                "timestamp" => time(),
                "public_id" => $public_id,
                "type" => \Cloudinary::option_get($options, "type"),
                "callback" => \Cloudinary::option_get($options, "callback"),
                "eager" => Uploader::build_eager(\Cloudinary::option_get($options, "eager")),
                "headers" => Uploader::build_custom_headers(\Cloudinary::option_get($options, "headers")),
                "tags" => \Cloudinary::encode_array(\Cloudinary::option_get($options, "tags")),
                "face_coordinates" => \Cloudinary::encode_double_array(\Cloudinary::option_get($options, "face_coordinates")),
                "custom_coordinates" => \Cloudinary::encode_double_array(\Cloudinary::option_get($options, "custom_coordinates"))
            );
            return Uploader::call_api("explicit", $params, $options);
        }

        public static function generate_sprite($tag, $options = array())
        {
            $transformation = \Cloudinary::generate_transformation_string(
              array_merge(array("fetch_format"=>\Cloudinary::option_get($options, "format")), $options));
            $params = array(
                "timestamp" => time(),
                "tag" => $tag,
                "async" => \Cloudinary::option_get($options, "async"),
                "notification_url" => \Cloudinary::option_get($options, "notification_url"),
                "transformation" => $transformation
            );
            return Uploader::call_api("sprite", $params, $options);
        }

        public static function multi($tag, $options = array())
        {
            $transformation = \Cloudinary::generate_transformation_string($options);
            $params = array(
                "timestamp" => time(),
                "tag" => $tag,
                "format" => \Cloudinary::option_get($options, "format"),
                "async" => \Cloudinary::option_get($options, "async"),
                "notification_url" => \Cloudinary::option_get($options, "notification_url"),
                "transformation" => $transformation
            );
            return Uploader::call_api("multi", $params, $options);
        }

        public static function explode($public_id, $options = array())
        {
            $transformation = \Cloudinary::generate_transformation_string($options);
            $params = array(
                "timestamp" => time(),
                "public_id" => $public_id,
                "format" => \Cloudinary::option_get($options, "format"),
                "type" => \Cloudinary::option_get($options, "type"),
                "notification_url" => \Cloudinary::option_get($options, "notification_url"),
                "transformation" => $transformation
            );
            return Uploader::call_api("explode", $params, $options);
        }

        // options may include 'exclusive' (boolean) which causes clearing this tag from all other resources
        public static function add_tag($tag, $public_ids = array(), $options = array())
        {
            $exclusive = \Cloudinary::option_get($options, "exclusive");
            $command = $exclusive ? "set_exclusive" : "add";
            return Uploader::call_tags_api($tag, $command, $public_ids, $options);
        }

        public static function remove_tag($tag, $public_ids = array(), $options = array())
        {
            return Uploader::call_tags_api($tag, "remove", $public_ids, $options);
        }

        public static function replace_tag($tag, $public_ids = array(), $options = array())
        {
            return Uploader::call_tags_api($tag, "replace", $public_ids, $options);
        }

        public static function call_tags_api($tag, $command, $public_ids = array(), &$options = array())
        {
            $params = array(
                "timestamp" => time(),
                "tag" => $tag,
                "public_ids" => \Cloudinary::build_array($public_ids),
                "type" => \Cloudinary::option_get($options, "type"),
                "command" => $command
            );
            return Uploader::call_api("tags", $params, $options);
        }

        private static $TEXT_PARAMS = array("public_id", "font_family", "font_size", "font_color", "text_align", "font_weight", "font_style", "background", "opacity", "text_decoration");

        public static function text($text, $options = array())
        {
            $params = array("timestamp" => time(), "text" => $text);
            foreach (Uploader::$TEXT_PARAMS as $key) {
                $params[$key] = \Cloudinary::option_get($options, $key);
            }
            return Uploader::call_api("text", $params, $options);
        }

        public static function call_api($action, $params, $options = array(), $file = NULL)
        {
            $return_error = \Cloudinary::option_get($options, "return_error");
            if (!\Cloudinary::option_get($options, "unsigned")) {
                $params = \Cloudinary::sign_request($params, $options);
            }

            $api_url = \Cloudinary::cloudinary_api_url($action, $options);

            # Serialize params
            $api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params)); 

            $ch = curl_init($api_url);

            $post_params = array();
            if ($file) {
                if (!preg_match('/^@|^https?:|^s3:|^data:[^;]*;base64,([a-zA-Z0-9\/+\n=]+)$/', $file)) {
                    if (function_exists("curl_file_create")) {
                        $post_params['file'] = curl_file_create($file);
                        $post_params['file']->setPostFilename($file);
                    } else {
                        $post_params["file"] = "@" . $file;
                    }
                } else {
                    $post_params["file"] = $file;
                }
            }

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            curl_setopt($ch, CURLOPT_CAINFO,realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."cacert.pem");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, \Cloudinary::USER_AGENT);
            curl_setopt($ch, CURLOPT_PROXY, \Cloudinary::option_get($options, "api_proxy", \Cloudinary::config_get("api_proxy")));
            
            $response = curl_exec($ch);
            $curl_error = NULL;
            if(curl_errno($ch))
            {
                $curl_error = curl_error($ch);
            }

            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response_data = $response;

            curl_close($ch);
            if ($curl_error != NULL) {
                throw new \Cloudinary\Error("Error in sending request to server - " . $curl_error);
            }
            if ($code != 200 && $code != 400 && $code != 500 && $code != 401 && $code != 404) {
                throw new \Cloudinary\Error("Server returned unexpected status code - " . $code . " - " . $response_data, $code);
            }
            $result = json_decode($response_data, TRUE);
            if ($result == NULL) {
                throw new \Cloudinary\Error("Error parsing server response (" . $code . ") - " . $response_data);
            }
            if (isset($result["error"])) {
                if ($return_error) {
                    $result["error"]["http_code"] = $code;
                } else {
                    throw new \Cloudinary\Error($result["error"]["message"], $code);
                }
            }
            return $result;
        }
        protected static function build_eager($transformations) {
            $eager = array();
            foreach (\Cloudinary::build_array($transformations) as $trans) {
                $transformation = $trans;
                $format = \Cloudinary::option_consume($tranformation, "format");
                $single_eager = implode("/", array_filter(array(\Cloudinary::generate_transformation_string($transformation), $format)));
                array_push($eager, $single_eager);
            }
            return implode("|", $eager);
        }

        protected static function build_custom_headers($headers) {
            if ($headers == NULL) {
                return NULL;
            } elseif (is_string($headers)) {
                return $headers;
            } elseif ($headers == array_values($headers)) {
                return implode("\n", $headers);
            } else {
                $join_pair = function($key, $value) { return $key . ": " . $value; };
                return implode("\n", array_map($join_pair, array_keys($headers), array_values($headers)));
            }
        }  
    }
}
