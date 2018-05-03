<?php

namespace {

    function cl_upload_url($options = array())
    {
        if (!@$options["resource_type"]) {
            $options["resource_type"] = "auto";
        }
        $endpoint = array_key_exists('chunk_size', $options) ? 'upload_chunked' : 'upload';

        return Cloudinary::cloudinary_api_url($endpoint, $options);
    }

    function cl_upload_tag_params($options = array())
    {
        $params = Cloudinary\Uploader::build_upload_params($options);
        if (Cloudinary::option_get($options, "unsigned")) {
            $params = array_filter(
                $params,
                function ($v) {
                    return !is_null($v) && ($v !== "");
                }
            );
        } else {
            $params = Cloudinary::sign_request($params, $options);
        }

        return json_encode($params);
    }

    function cl_unsigned_image_upload_tag($field, $upload_preset, $options = array())
    {
        return cl_image_upload_tag(
            $field,
            array_merge($options, array("unsigned" => true, "upload_preset" => $upload_preset))
        );
    }

    function cl_image_upload_tag($field, $options = array())
    {
        return cl_upload_tag($field, $options);
    }

    function cl_upload_tag($field, $options = array())
    {
        $html_options = Cloudinary::option_get($options, "html", array());

        $classes = array("cloudinary-fileupload");
        if (isset($html_options["class"])) {
            array_unshift($classes, Cloudinary::option_consume($html_options, "class"));
        }
        $tag_options = array_merge(
            $html_options,
            array(
                "type" => "file",
                "name" => "file",
                "data-url" => cl_upload_url($options),
                "data-form-data" => cl_upload_tag_params($options),
                "data-cloudinary-field" => $field,
                "class" => implode(" ", $classes),
            )
        );
        if (array_key_exists('chunk_size', $options)) {
            $tag_options['data-max-chunk-size'] = $options['chunk_size'];
        }

        return '<input ' . Cloudinary::html_attrs($tag_options) . '/>';
    }

    function cl_form_tag($callback_url, $options = array())
    {
        $form_options = Cloudinary::option_get($options, "form", array());

        $options["callback_url"] = $callback_url;

        $params = Cloudinary\Uploader::build_upload_params($options);
        $params = Cloudinary::sign_request($params, $options);

        $api_url = Cloudinary::cloudinary_api_url("upload", $options);

        $form = "<form enctype='multipart/form-data' action='" . $api_url . "' method='POST' " .
            Cloudinary::html_attrs($form_options) . ">\n";
        foreach ($params as $key => $value) {
            $attributes =  array(
                "name" => $key,
                "value" => $value,
                "type" => "hidden",
            );
            $form .= "<input " . Cloudinary::html_attrs($attributes) . "/>\n";
        }
        $form .= "</form>\n";

        return $form;
    }


    /**
     * @internal Helper function. Gets or populates srcset breakpoints using provided parameters
     *
     * Either the breakpoints or min_width, max_width, max_images must be provided.
     *
     * @param array $srcset_data {
     *
     *      @var array  breakpoints An array of breakpoints.
     *      @var int    min_width   Minimal width of the srcset images.
     *      @var int    max_width   Maximal width of the srcset images.
     *      @var int    max_images  Number of srcset images to generate.
     * }
     *
     * @return array Array of breakpoints
     *
     * @throws InvalidArgumentException In case of invalid or missing parameters
     */
    function get_srcset_breakpoints($srcset_data)
    {
        $breakpoints = Cloudinary::option_get($srcset_data, "breakpoints", array());

        if (!empty($breakpoints)) {
            return $breakpoints;
        }

        foreach (array('min_width', 'max_width', 'max_images') as $arg) {
            if (empty($srcset_data[$arg]) || !is_numeric($srcset_data[$arg]) || is_string($srcset_data[$arg])) {
                throw new InvalidArgumentException('Either valid (min_width, max_width, max_images) ' .
                                                   'or breakpoints must be provided to the image srcset attribute');
            }
        }

        $min_width = $srcset_data['min_width'];
        $max_width = $srcset_data['max_width'];
        $max_images = $srcset_data['max_images'];

        if ($min_width > $max_width) {
            throw new InvalidArgumentException('min_width must be less than max_width');
        }

        if ($max_images <= 0) {
            throw new InvalidArgumentException('max_images must be a positive integer');
        } elseif ($max_images == 1) {
            // if user requested only 1 image in srcset, we return max_width one
            $min_width = $max_width;
        }

        $step_size = ceil(($max_width - $min_width) / ($max_images > 1 ? $max_images - 1 : 1));

        $curr_breakpoint = $min_width;

        while ($curr_breakpoint < $max_width) {
            array_push($breakpoints, $curr_breakpoint);
            $curr_breakpoint += $step_size;
        }

        array_push($breakpoints, $max_width);

        return $breakpoints;
    }

    /**
     * @internal Helper function. Generates a single srcset item url
     *
     * @param string    $public_id  Public ID of the resource
     * @param int       $width      Width in pixels of the srcset item
     * @param array     $options    Additional options
     *
     * @return mixed|null|string|string[] Resulting URL of the item
     */
    function generate_single_srcset_url($public_id, $width, $options)
    {
        $curr_options = Cloudinary::array_copy($options);
        /*
        The following line is used for the next purposes:
          1. Generate raw transformation string
          2. Cleanup transformation parameters from $curr_options.
        We call it intentionally even when the user provided custom transformation in srcset
        */
        $raw_transformation = Cloudinary::generate_transformation_string($curr_options);

        if (!empty($options["srcset"]["transformation"])) {
            $curr_options["transformation"] = $options["srcset"]["transformation"];
            $raw_transformation = Cloudinary::generate_transformation_string($curr_options);
        }

        $curr_options["raw_transformation"] = $raw_transformation . "/c_scale,w_{$width}";

        // We might still have width and height params left if they were provided.
        // We don't want to use them for the second time
        $unwanted_params = array('width', 'height');
        foreach ($unwanted_params as $key) {
            unset($curr_options[$key]);
        }

        return cloudinary_url_internal($public_id, $curr_options);
    }

    /**
     * @internal Helper function. Generates srcset attribute value of the HTML img tag
     *
     * @param array $srcset_data {
     *
     *      @var array  breakpoints An array of breakpoints.
     *      @var int    min_width   Minimal width of the srcset images.
     *      @var int    max_width   Maximal width of the srcset images.
     *      @var int    max_images  Number of srcset images to generate.
     * }
     *
     * @param array $options Additional options.
     *
     * @return string Resulting srcset attribute value
     *
     * @throws InvalidArgumentException In case of invalid or missing parameters
     */
    function generate_image_srcset_attribute($public_id, $srcset_data, $options = array())
    {
        if (empty($srcset_data)) {
            return null;
        }
        if (is_string($srcset_data)) {
            return $srcset_data;
        }

        $breakpoints = get_srcset_breakpoints($srcset_data);

        // The code below is a part of `cloudinary_url` code that affects $options.
        // We call it here, to make sure we get exactly the same behavior.
        // TODO: Refactor this code, unify it with `cloudinary_url` or fix `cloudinary_url` and remove it
        Cloudinary::check_cloudinary_field($public_id, $options);
        $type = Cloudinary::option_get($options, "type", "upload");

        if ($type == "fetch" && !isset($options["fetch_format"])) {
            $options["fetch_format"] = Cloudinary::option_consume($options, "format");
        }
        //END OF TODO

        $items = array();
        foreach ($breakpoints as $breakpoint) {
            array_push($items, generate_single_srcset_url($public_id, $breakpoint, $options) . " {$breakpoint}w");
        }

        return implode(", ", $items);
    }

    /**
     * @internal Helper function. Generates sizes attribute value of the HTML img tag
     *
     * @param array $srcset_data {
     *
     *      @var array  breakpoints An array of breakpoints.
     *      @var int    min_width   Minimal width of the srcset images.
     *      @var int    max_width   Maximal width of the srcset images.
     *      @var int    max_images  Number of srcset images to generate.
     * }
     *
     * @return string Resulting sizes attribute value
     *
     * @throws InvalidArgumentException In case of invalid or missing parameters
     */
    function generate_image_sizes_attribute($srcset_data)
    {
        if (empty($srcset_data) or is_string($srcset_data)) {
            return null;
        }

        $breakpoints = get_srcset_breakpoints($srcset_data);

        $sizes_items = array();
        foreach ($breakpoints as $breakpoint) {
            array_push($sizes_items, "(max-width: {$breakpoint}px) {$breakpoint}px");
        }

        return implode(", ", $sizes_items);
    }

    /**
     * Generates HTML img tag
     *
     * @param string    $public_id  Public ID of the resource
     *
     * @param array     $options    Additional options
     *
     * Examples:
     *
     * W/H are not sent to cloudinary
     * cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello")
     *
     * W/H are sent to cloudinary
     * cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello", "crop"=>"fit")
     *
     * @return string Resulting img tag
     *
     */
    function cl_image_tag($public_id, $options = array())
    {
        $original_options = null;

        if (!empty($options['srcset'])) {
            // Since cloudinary_url is destructive, we need to save a copy of original options passed to this function
            $original_options =  Cloudinary::array_copy($options);
        }

        $source = cloudinary_url_internal($public_id, $options);

        if (isset($options["html_width"])) {
            $options["width"] = Cloudinary::option_consume($options, "html_width");
        }
        if (isset($options["html_height"])) {
            $options["height"] = Cloudinary::option_consume($options, "html_height");
        }

        $client_hints = Cloudinary::option_consume($options, "client_hints", Cloudinary::config_get("client_hints"));
        $responsive = Cloudinary::option_consume($options, "responsive");
        $hidpi = Cloudinary::option_consume($options, "hidpi");
        if (($responsive || $hidpi) && !$client_hints) {
            $options["data-src"] = $source;
            $classes = array($responsive ? "cld-responsive" : "cld-hidpi");
            $current_class = Cloudinary::option_consume($options, "class");
            if ($current_class) {
                array_unshift($classes, $current_class);
            }
            $options["class"] = implode(" ", $classes);
            $source = Cloudinary::option_consume(
                $options,
                "responsive_placeholder",
                Cloudinary::config_get("responsive_placeholder")
            );
            if ($source == "blank") {
                $source = Cloudinary::BLANK;
            }
        }
        $html = "<img ";

        if ($source) {
            $html .= "src='" . htmlspecialchars($source, ENT_QUOTES) . "' ";
        }

        if (!empty($options["srcset"])) {
            $srcset_data = $options["srcset"];
            $options["srcset"] = generate_image_srcset_attribute($public_id, $srcset_data, $original_options);

            if (!empty($srcset_data["sizes"]) && $srcset_data["sizes"] === true) {
                $options["sizes"] = generate_image_sizes_attribute($srcset_data);
            }

            // width and height attributes override srcset behavior, they should be removed from html attributes.
            $unwanted_attributes = array('width', 'height');
            foreach ($unwanted_attributes as $key) {
                unset($options[$key]);
            }
        }

        $attr_data = Cloudinary::option_consume($options, 'attributes', array());
        // Explicitly provided attributes override options
        $attributes = array_merge($options, $attr_data);

        $html .= Cloudinary::html_attrs($attributes) . "/>";

        return $html;
    }

    function fetch_image_tag($url, $options = array())
    {
        $options["type"] = "fetch";

        return cl_image_tag($url, $options);
    }

    function facebook_profile_image_tag($profile, $options = array())
    {
        $options["type"] = "facebook";

        return cl_image_tag($profile, $options);
    }

    function gravatar_profile_image_tag($email, $options = array())
    {
        $options["type"] = "gravatar";
        $options["format"] = "jpg";

        return cl_image_tag(md5(strtolower(trim($email))), $options);
    }

    function twitter_profile_image_tag($profile, $options = array())
    {
        $options["type"] = "twitter";

        return cl_image_tag($profile, $options);
    }

    function twitter_name_profile_image_tag($profile, $options = array())
    {
        $options["type"] = "twitter_name";

        return cl_image_tag($profile, $options);
    }

    function cloudinary_js_config()
    {
        $params = array();
        foreach (Cloudinary::$JS_CONFIG_PARAMS as $param) {
            $value = Cloudinary::config_get($param);
            if ($value) {
                $params[$param] = $value;
            }
        }

        return "<script type='text/javascript'>\n" .
            "$.cloudinary.config(" . json_encode($params) . ");\n" .
            "</script>\n";
    }

    function cloudinary_url($source, $options = array())
    {
        return cloudinary_url_internal($source, $options);
    }

    function cloudinary_url_internal($source, &$options = array())
    {
        if (!isset($options["secure"])) {
            $options["secure"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        }

        return Cloudinary::cloudinary_url($source, $options);
    }

    function cl_sprite_url($tag, $options = array())
    {
        if (substr($tag, -strlen(".css")) != ".css") {
            $options["format"] = "css";
        }
        $options["type"] = "sprite";

        return cloudinary_url_internal($tag, $options);
    }

    function cl_sprite_tag($tag, $options = array())
    {
        return "<link rel='stylesheet' type='text/css' href='" .
            htmlspecialchars(cl_sprite_url($tag, $options), ENT_QUOTES) .
            "'>";
    }

    function default_poster_options()
    {
        return array('format' => 'jpg', 'resource_type' => 'video');
    }

    function default_source_types()
    {
        return array('webm', 'mp4', 'ogv');
    }

    # Returns a url for the given source with +options+
    function cl_video_path($source, $options = array())
    {
        $options = array_merge(array('resource_type' => 'video'), $options);

        return cloudinary_url_internal($source, $options);
    }

    # Returns an HTML <tt>img</tt> tag with the thumbnail for the given video +source+ and +options+
    function cl_video_thumbnail_tag($source, $options = array())
    {
        return cl_image_tag($source, array_merge(default_poster_options(), $options));
    }

    # Returns a url for the thumbnail for the given video +source+ and +options+
    function cl_video_thumbnail_path($source, $options = array())
    {
        $options = array_merge(default_poster_options(), $options);

        return cloudinary_url_internal($source, $options);
    }

    # Creates an HTML video tag for the provided +source+
    #
    # ==== Options
    # * <tt>source_types</tt> - Specify which source type the tag should include. defaults to webm, mp4 and ogv.
    # * <tt>source_transformation</tt> - specific transformations to use for a specific source type.
    # * <tt>poster</tt> - override default thumbnail:
    #   * url: provide an ad hoc url
    #   * options: with specific poster transformations and/or Cloudinary +:public_id+
    #
    # ==== Examples
    #   cl_video_tag("mymovie.mp4")
    #   cl_video_tag("mymovie.mp4", array('source_types' => 'webm'))
    #   cl_video_tag("mymovie.ogv", array('poster' => "myspecialplaceholder.jpg"))
    #   cl_video_tag("mymovie.webm", array('source_types' => array('webm', 'mp4'), 'poster' => array('effect' => 'sepia')))
    function cl_video_tag($source, $options = array())
    {
        $source = preg_replace('/\.(' . implode('|', default_source_types()) . ')$/', '', $source);

        $source_types = Cloudinary::option_consume($options, 'source_types', array());
        $source_transformation = Cloudinary::option_consume($options, 'source_transformation', array());
        $fallback = Cloudinary::option_consume($options, 'fallback_content', '');

        if (empty($source_types)) {
            $source_types = default_source_types();
        }
        $video_options = $options;

        if (array_key_exists('poster', $video_options)) {
            if (is_array($video_options['poster'])) {
                if (array_key_exists('public_id', $video_options['poster'])) {
                    $video_options['poster'] = cloudinary_url_internal(
                        $video_options['poster']['public_id'],
                        $video_options['poster']
                    );
                } else {
                    $video_options['poster'] = cl_video_thumbnail_path($source, $video_options['poster']);
                }
            }
        } else {
            $video_options['poster'] = cl_video_thumbnail_path($source, $options);
        }

        if (empty($video_options['poster'])) {
            unset($video_options['poster']);
        }


        $html = '<video ';

        if (!array_key_exists('resource_type', $video_options)) {
            $video_options['resource_type'] = 'video';
        }
        $multi_source = is_array($source_types);
        if (!$multi_source) {
            $source .= '.' . $source_types;
        }
        $src = cloudinary_url_internal($source, $video_options);
        if (!$multi_source) {
            $video_options['src'] = $src;
        }
        if (isset($video_options["html_width"])) {
            $video_options['width'] = Cloudinary::option_consume($video_options, 'html_width');
        }
        if (isset($video_options['html_height'])) {
            $video_options['height'] = Cloudinary::option_consume($video_options, 'html_height');
        }
        $html .= Cloudinary::html_attrs($video_options) . '>';

        if ($multi_source) {
            foreach ($source_types as $source_type) {
                $transformation = Cloudinary::option_consume($source_transformation, $source_type, array());
                $transformation = array_merge($options, $transformation);
                $src = cl_video_path($source . '.' . $source_type, $transformation);
                $video_type = (($source_type == 'ogv') ? 'ogg' : $source_type);
                $mime_type = "video/$video_type";
                $html .= '<source ' . Cloudinary::html_attrs(array('src' => $src, 'type' => $mime_type)) . '>';
            }
        }

        $html .= $fallback;
        $html .= '</video>';

        return $html;
    }
}
