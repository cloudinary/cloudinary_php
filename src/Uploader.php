<?php
namespace Cloudinary {

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
                "notification_url" => \Cloudinary::option_get($options, "notification_url"),
                "eager_notification_url" => \Cloudinary::option_get($options, "eager_notification_url"),
                "eager_async" => \Cloudinary::option_get($options, "eager_async"),
                "tags" => implode(",", \Cloudinary::build_array(\Cloudinary::option_get($options, "tags"))));
            return array_filter($params);
        }

        public static function upload($file, $options = array())
        {
            $params = Uploader::build_upload_params($options);
            return Uploader::call_api("upload", $params, $options, $file);
        }

        public static function destroy($public_id, $options = array())
        {
            $params = array(
                "timestamp" => time(),
                "type" => \Cloudinary::option_get($options, "type"),
                "public_id" => $public_id
            );
            return Uploader::call_api("destroy", $params, $options);
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
                "tags" => implode(",", \Cloudinary::build_array(\Cloudinary::option_get($options, "tags")))
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
            $exclusive = \Cloudinary::option_get("exclusive");
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
            $api_key = \Cloudinary::option_get($options, "api_key", \Cloudinary::config_get("api_key"));
            if (!$api_key) throw new \InvalidArgumentException("Must supply api_key");
            $api_secret = \Cloudinary::option_get($options, "api_secret", \Cloudinary::config_get("api_secret"));
            if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");

            $params["signature"] = \Cloudinary::api_sign_request($params, $api_secret);
            $params["api_key"] = $api_key;

            # Remove blank parameters
            $params = array_filter($params);

            $api_url = \Cloudinary::cloudinary_api_url($action, $options);
            $ch = curl_init($api_url);

            if ($file) {
                if (!preg_match('/^https?:/', $file) && $file[0] != "@") {
                    $params["file"] = "@" . $file;
                } else {
                    $params["file"] = $file;
                }
            }

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CAINFO,realpath(dirname(__FILE__))."/cacert.pem");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
                throw new \Exception("Error in sending request to server - " . $curl_error);
            }
            if ($code != 200 && $code != 400 && $code != 500 && $code != 401 && $code != 404) {
                throw new \Exception("Server returned unexpected status code - " . $code . " - " . $response_data);
            }
            $result = json_decode($response_data, TRUE);
            if ($result == NULL) {
                throw new \Exception("Error parsing server response (" . $code . ") - " . $response_data);
            }
            if (isset($result["error"])) {
                if ($return_error) {
                    $result["error"]["http_code"] = $code;
                } else {
                    throw new \Exception($result["error"]["message"]);
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

namespace {

    function cl_image_upload_tag($field, $options = array())
    {
        $html_options = Cloudinary::option_get($options, "html", array());
        if (!isset($options["resource_type"])) $options["resource_type"] = "auto";
        $cloudinary_upload_url = Cloudinary::cloudinary_api_url("upload", $options);

        $api_key = Cloudinary::option_get($options, "api_key", Cloudinary::config_get("api_key"));
        if (!$api_key) throw new \InvalidArgumentException("Must supply api_key");
        $api_secret = Cloudinary::option_get($options, "api_secret", Cloudinary::config_get("api_secret"));
        if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");

        $params = Cloudinary\Uploader::build_upload_params($options);
        $params["signature"] = Cloudinary::api_sign_request($params, $api_secret);
        $params["api_key"] = $api_key;

        # Remove blank parameters
        $params = array_filter($params);

        $classes = array("cloudinary-fileupload");
        if (isset($html_options["class"])) {
            array_unshift($classes, Cloudinary::option_consume($html_options, "class"));
        }
        $tag_options = array_merge($html_options, array("type" => "file", "name" => "file",
            "data-url" => $cloudinary_upload_url,
            "data-form-data" => json_encode($params),
            "data-cloudinary-field" => $field,
            "class" => implode(" ", $classes),
        ));
        return '<input ' . Cloudinary::html_attrs($tag_options) . '/>';
    }

    function cl_form_tag($callback_url, $options = array())
    {
        $form_options = Cloudinary::option_get($options, "form", array());

        $options["callback_url"] = $callback_url;
        $params = Cloudinary\Uploader::build_upload_params($options);
        $params["signature"] = Cloudinary::api_sign_request($params, Cloudinary::config_get("api_secret"));
        $params["api_key"] = Cloudinary::config_get("api_key");

        $api_url = Cloudinary::cloudinary_api_url("upload", $options);

        $form = "<form enctype='multipart/form-data' action='" . $api_url . "' method='POST' " . Cloudinary::html_attrs($form_options) . ">\n";
        foreach ($params as $key => $value) {
            $form .= "<input " . Cloudinary::html_attrs(array("name" => $key, "value" => $value, "type" => "hidden")) . "/>\n";
        }
        $form .= "</form>\n";

        return $form;
    }
}
?>
