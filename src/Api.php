<?php

namespace Cloudinary {

    class Api
    {
        public static $CLOUDINARY_API_ERROR_CLASSES = array(
            400 => "\Cloudinary\Api\BadRequest",
            401 => "\Cloudinary\Api\AuthorizationRequired",
            403 => "\Cloudinary\Api\NotAllowed",
            404 => "\Cloudinary\Api\NotFound",
            409 => "\Cloudinary\Api\AlreadyExists",
            420 => "\Cloudinary\Api\RateLimited",
            500 => "\Cloudinary\Api\GeneralError",
        );

        public function ping($options = array())
        {
            return $this->call_api("get", array("ping"), array(), $options);
        }

        public function usage($options = array())
        {
            return $this->call_api("get", array("usage"), array(), $options);
        }

        public function resource_types($options = array())
        {
            return $this->call_api("get", array("resources"), array(), $options);
        }

        public function resources($options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type");
            $uri = array("resources", $resource_type);
            if ($type) {
                array_push($uri, $type);
            }
            $params = $this->only(
                $options,
                array(
                    "next_cursor",
                    "max_results",
                    "prefix",
                    "tags",
                    "context",
                    "moderations",
                    "direction",
                    "start_at",
                )
            );

            return $this->call_api("get", $uri, $params, $options);
        }

        public function resources_by_tag($tag, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $uri = array("resources", $resource_type, "tags", $tag);
            $params = $this->only(
                $options,
                array("next_cursor", "max_results", "tags", "context", "moderations", "direction")
            );

            return $this->call_api("get", $uri, $params, $options);
        }

        public function resources_by_context($key, $value = null, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $uri = array("resources", $resource_type, "context");
            $params = $this->only(
                $options,
                array("next_cursor", "max_results", "tags", "context", "moderations", "direction")
            );
            $params["key"] = $key;
            $params["value"] = $value;

            return $this->call_api("get", $uri, $params, $options);
        }

        public function resources_by_moderation($kind, $status, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $uri = array("resources", $resource_type, "moderations", $kind, $status);
            $params = $this->only(
                $options,
                array("next_cursor", "max_results", "tags", "context", "moderations", "direction")
            );

            return $this->call_api("get", $uri, $params, $options);
        }

        public function resources_by_ids($public_ids, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type);
            $params = array_merge($options, array("public_ids" => $public_ids));
            $params = $this->only($params, array("public_ids", "tags", "moderations", "context"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function resource($public_id, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type, $public_id);
            $params = $this->only(
                $options,
                array("exif", "colors", "faces", "image_metadata", "phash", "pages", "coordinates", "max_results")
            );

            return $this->call_api("get", $uri, $params, $options);
        }

        public function restore($public_ids, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type, "restore");
            $params = array_merge($options, array("public_ids" => $public_ids));

            return $this->call_api("post", $uri, $params, $options);
        }

        public function update($public_id, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type, $public_id);

            $tags = \Cloudinary::option_get($options, "tags");
            $context = \Cloudinary::option_get($options, "context");
            $face_coordinates = \Cloudinary::option_get($options, "face_coordinates");
            $custom_coordinates = \Cloudinary::option_get($options, "custom_coordinates");
            $access_control = \Cloudinary::option_get($options, "access_control");

            $primitive_options = $this->only(
                $options,
                array(
                    "moderation_status",
                    "raw_convert",
                    "ocr",
                    "categorization",
                    "detection",
                    "similarity_search",
                    "auto_tagging",
                    "background_removal",
                    "quality_override",
                )
            );

            $array_options = array(
                "tags" => $tags ? implode(",", \Cloudinary::build_array($tags)) : $tags,
                "context" => $context ? \Cloudinary::encode_assoc_array($context) : $context,
                "face_coordinates" => $face_coordinates ? \Cloudinary::encode_double_array(
                    $face_coordinates
                ) : $face_coordinates,
                "custom_coordinates" => $custom_coordinates ? \Cloudinary::encode_double_array(
                    $custom_coordinates
                ) : $custom_coordinates,
                "access_control" => \Cloudinary::encode_array_to_json($access_control),
            );

            $update_options = array_merge($primitive_options, $array_options);

            return $this->call_api("post", $uri, $update_options, $options);
        }

        public function delete_resources($public_ids, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type);
            $params = $this->prepare_delete_resource_params($options, ["public_ids" => $public_ids]);

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function delete_resources_by_prefix($prefix, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type);
            $params = $this->prepare_delete_resource_params($options, ["prefix" => $prefix]);

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function delete_all_resources($options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type = \Cloudinary::option_get($options, "type", "upload");
            $uri = array("resources", $resource_type, $type);
            $params = $this->prepare_delete_resource_params($options, ["all" => true]);

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function delete_resources_by_tag($tag, $options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $uri = array("resources", $resource_type, "tags", $tag);
            $params = $this->prepare_delete_resource_params($options);

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function delete_derived_resources($derived_resource_ids, $options = array())
        {
            $uri = array("derived_resources");
            $params = array("derived_resource_ids" => $derived_resource_ids);

            return $this->call_api("delete", $uri, $params, $options);
        }

        /**
         * Delete derived resources identified by transformation for the provided public_ids
         * @param string|array $public_ids The resources the derived resources belong to
         * @param string|array $transformations The transformation(s) associated with the derived resources
         * @param array $options Hash of options
         * @return Api\Response
         * @throws Api\GeneralError
         */
        public function delete_derived_by_transformation(
            $public_ids,
            $transformations = array(),
            $options = array()
        ) {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $type          = \Cloudinary::option_get($options, "type", "upload");
            $uri           = ["resources", $resource_type, $type];
            $params = [
                "public_ids" => \Cloudinary::build_array($public_ids),
                "keep_original" => true,
            ];
            $params["transformations"] = \Cloudinary::build_eager($transformations);
            $params = array_merge($params, $this->only($options, ["invalidate"]));

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function tags($options = array())
        {
            $resource_type = \Cloudinary::option_get($options, "resource_type", "image");
            $uri = array("tags", $resource_type);
            $params = $this->only($options, array("next_cursor", "max_results", "prefix"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function transformations($options = array())
        {
            $uri = array("transformations");
            $params = $this->only($options, array("next_cursor", "max_results"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function transformation($transformation, $options = array())
        {
            $uri = array("transformations", $this->transformation_string($transformation));
            $params = $this->only($options, array("next_cursor", "max_results"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function delete_transformation($transformation, $options = array())
        {
            $uri = array("transformations", $this->transformation_string($transformation));
            $params = array();
            if (isset($options["invalidate"])) {
                $params["invalidate"] = $options["invalidate"];
            }

            return $this->call_api("delete", $uri, $params, $options);
        }

        # updates - currently only supported update is the "allowed_for_strict" boolean flag
        public function update_transformation($transformation, $updates = array(), $options = array())
        {
            $uri = array("transformations", $this->transformation_string($transformation));
            $params = $this->only($updates, array("allowed_for_strict"));
            if (isset($updates["unsafe_update"])) {
                $params["unsafe_update"] = $this->transformation_string($updates["unsafe_update"]);
            }

            return $this->call_api("put", $uri, $params, $options);
        }

        public function create_transformation($name, $definition, $options = array())
        {
            $uri = array("transformations", $name);
            $params = array("transformation" => $this->transformation_string($definition));

            return $this->call_api("post", $uri, $params, $options);
        }

        public function upload_presets($options = array())
        {
            $uri = array("upload_presets");
            $params = $this->only($options, array("next_cursor", "max_results"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function upload_preset($name, $options = array())
        {
            $uri = array("upload_presets", $name);

            return $this->call_api("get", $uri, $this->only($options, array("max_results")), $options);
        }

        public function delete_upload_preset($name, $options = array())
        {
            $uri = array("upload_presets", $name);

            return $this->call_api("delete", $uri, array(), $options);
        }

        public function update_upload_preset($name, $options = array())
        {
            $uri = array("upload_presets", $name);
            $params = \Cloudinary\Uploader::build_upload_params($options);
            $params = array_merge($params, $this->only($options, array("unsigned", "disallow_public_id")));

            return $this->call_api("put", $uri, $params, $options);
        }

        public function create_upload_preset($options = array())
        {
            $params = \Cloudinary\Uploader::build_upload_params($options);
            $params = array_merge($params, $this->only($options, array("name", "unsigned", "disallow_public_id")));

            return $this->call_api("post", array("upload_presets"), $params, $options);
        }

        public function root_folders($options = array())
        {
            return $this->call_api("get", array("folders"), array(), $options);
        }

        public function subfolders($of_folder_path, $options = array())
        {
            $uri = array("folders", $of_folder_path);

            return $this->call_api("get", $uri, array(), $options);
        }

        public function upload_mappings($options = array())
        {
            $uri = array("upload_mappings");
            $params = $this->only($options, array("next_cursor", "max_results"));

            return $this->call_api("get", $uri, $params, $options);
        }

        public function upload_mapping($name, $options = array())
        {
            $uri = array("upload_mappings");
            $params = array("folder" => $name);

            return $this->call_api("get", $uri, $params, $options);
        }

        public function delete_upload_mapping($name, $options = array())
        {
            $uri = array("upload_mappings");
            $params = array("folder" => $name);

            return $this->call_api("delete", $uri, $params, $options);
        }

        public function update_upload_mapping($name, $options = array())
        {
            $uri = array("upload_mappings");
            $params = array_merge(array("folder" => $name), $this->only($options, array("template")));

            return $this->call_api("put", $uri, $params, $options);
        }

        public function create_upload_mapping($name, $options = array())
        {
            $uri = array("upload_mappings");
            $params = array_merge(array("folder" => $name), $this->only($options, array("template")));

            return $this->call_api("post", $uri, $params, $options);
        }

        /**
         * List all streaming profiles associated with the current customer
         * @param array $options options
         * @return Api\Response An array with a "data" key for results
         * @throws Api\GeneralError
         */
        public function list_streaming_profiles($options = array())
        {
            return $this->call_api("get", array("streaming_profiles"), array(), $options);
        }

        /**
         * Get the information of a single streaming profile
         * @param string $name the name of the profile
         * @param array $options other options
         * @return Api\Response An array with a "data" key for results
         * @throws Api\GeneralError
         */
        public function get_streaming_profile($name, $options = array())
        {
            $uri = array("streaming_profiles/" . $name);
            return $this->call_api("get", $uri, array(), $options);
        }

        /**
         * Delete a streaming profile information. Predefined profiles are restored to the default setting.
         * @param string $name the name of the streaming profile to delete
         * @param array $options additional options
         * @return Api\Response
         * @throws Api\GeneralError
         */
        public function delete_streaming_profile($name, $options = array())
        {
            $uri = array("streaming_profiles/" . $name);
            return $this->call_api("delete", $uri, array(), $options);
        }

        /**
         * Update an existing streaming profile
         * @param string $name the name of the prodile
         * @param array $options additional options
         * @return Api\Response
         * @throws Api\GeneralError
         */
        public function update_streaming_profile($name, $options = array())
        {
            $uri = array("streaming_profiles/" . $name);
            $params = $this->prepare_streaming_profile_params($options);
            return $this->call_api("put", $uri, $params, $options);
        }

        /**
         * Create a new streaming profile
         * @param string $name the name of the new profile. if the name is of a predefined profile,
         * the profile will be modified.
         * @param array $options additional options
         * @return Api\Response
         * @throws Api\GeneralError
         */
        public function create_streaming_profile($name, $options = array())
        {
            $uri = array("streaming_profiles");
            $params = $this->prepare_streaming_profile_params($options);
            $params["name"] = $name;

            return $this->call_api("post", $uri, $params, $options);
        }

        public function call_api($method, $uri, $params, &$options)
        {
            $prefix = \Cloudinary::option_get(
                $options,
                "upload_prefix",
                \Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com")
            );
            $cloud_name = \Cloudinary::option_get($options, "cloud_name", \Cloudinary::config_get("cloud_name"));
            if (!$cloud_name) {
                throw new \InvalidArgumentException("Must supply cloud_name");
            }
            $api_key = \Cloudinary::option_get($options, "api_key", \Cloudinary::config_get("api_key"));
            if (!$api_key) {
                throw new \InvalidArgumentException("Must supply api_key");
            }
            $api_secret = \Cloudinary::option_get($options, "api_secret", \Cloudinary::config_get("api_secret"));
            if (!$api_secret) {
                throw new \InvalidArgumentException("Must supply api_secret");
            }
            $api_url = implode("/", array_merge(array($prefix, "v1_1", $cloud_name), $uri));
            $params = array_filter(
                $params,
                function ($v) {
                    return !is_null($v) && ($v !== "");
                }
            );
            if ($method == "get") {
                $api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params));
            }

            $ch = curl_init($api_url);

            if ($method != "get") {
                $post_params = array();
                if (array_key_exists("content_type", $options) && $options["content_type"] == 'application/json') {
                    $headers = array(
                        "Content-type: application/json",
                        "Accept: application/json",
                    );
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $post_params = json_encode($params);
                } else {
                    foreach ($params as $key => $value) {
                        if (is_array($value)) {
                            $i = 0;
                            foreach ($value as $item) {
                                $post_params[$key . "[$i]"] = $item;
                                $i++;
                            }
                        } else {
                            $post_params[$key] = $value;
                        }
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            }
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "{$api_key}:{$api_secret}");
            curl_setopt($ch, CURLOPT_CAINFO, realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "cacert.pem");
            curl_setopt($ch, CURLOPT_USERAGENT, \Cloudinary::userAgent());
            curl_setopt(
                $ch,
                CURLOPT_PROXY,
                \Cloudinary::option_get($options, "api_proxy", \Cloudinary::config_get("api_proxy"))
            );
            $response = $this->execute($ch);
            $curl_error = null;
            if (curl_errno($ch)) {
                $curl_error = curl_error($ch);
            }
            curl_close($ch);
            if ($curl_error != null) {
                throw new \Cloudinary\Api\GeneralError("Error in sending request to server - " . $curl_error);
            }
            if ($response->responseCode == 200) {
                return new \Cloudinary\Api\Response($response);
            } else {
                $exception_class = \Cloudinary::option_get(
                    self::$CLOUDINARY_API_ERROR_CLASSES,
                    $response->responseCode
                );
                if (!$exception_class) {
                    throw new \Cloudinary\Api\GeneralError(
                        "Server returned unexpected status code - {$response->responseCode} - {$response->body}"
                    );
                }
                $json = $this->parse_json_response($response);
                throw new $exception_class($json["error"]["message"]);
            }
        }

        # Based on http://snipplr.com/view/17242/
        protected function execute($ch)
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

        public static function parse_json_response($response)
        {
            $result = json_decode($response->body, true);
            if ($result == null) {
                $error = json_last_error();
                throw new \Cloudinary\Api\GeneralError(
                    "Error parsing server response ({$response->responseCode}) - {$response->body}. Got - {$error}"
                );
            }

            return $result;
        }

        protected function only(&$hash, $keys)
        {
            $result = array();
            foreach ($keys as $key) {
                if (isset($hash[$key])) {
                    $result[$key] = $hash[$key];
                }
            }

            return $result;
        }

        protected function transformation_string($transformation)
        {
            if (is_string($transformation)) {
                return $transformation;
            }

            return \Cloudinary::generate_transformation_string($transformation);
        }

        /**
         * Prepare streaming profile parameters for API calls
         * @param array $options the options passed to the API
         * @return array A single profile parameters
         */
        protected function prepare_streaming_profile_params($options)
        {
            $params = $this->only($options, array("display_name"));
            if (isset($options['representations'])) {
                $array_map = array_map(
                    function ($representation) {
                        return array("transformation" => \Cloudinary::generate_transformation_string($representation));
                    },
                    $options['representations']
                );
                $params["representations"] = json_encode($array_map);
            }

            return $params;
        }

        protected function prepare_delete_resource_params($options, $params = [])
        {
            $filtered = $this->only($options, ["keep_original", "next_cursor", "invalidate"]);
            if (isset($options["transformations"])) {
                $filtered["transformations"] = \Cloudinary::build_eager($options["transformations"]);
            }
            return array_merge($params, $filtered);
        }
    }

}
