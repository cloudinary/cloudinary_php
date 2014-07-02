<?php

namespace {
    function cl_upload_url($options = array()) 
    {
        if (!@$options["resource_type"]) $options["resource_type"] = "auto";
        return Cloudinary::cloudinary_api_url("upload", $options);      
    }

    function cl_upload_tag_params($options = array()) 
    {
        $params = Cloudinary\Uploader::build_upload_params($options);
        if (Cloudinary::option_get($options, "unsigned")) {
          $params = array_filter($params,function($v){ return !is_null($v) && ($v !== "" );});
        } else {
          $params = Cloudinary::sign_request($params, $options);
        }
        return json_encode($params);
    }
    
    function cl_unsigned_image_upload_tag($field, $upload_preset, $options = array())
    {
      return cl_image_upload_tag($field, array_merge($options, array("unsigned"=>TRUE, "upload_preset"=>$upload_preset)));
    }

    function cl_image_upload_tag($field, $options = array())
    {
        $html_options = Cloudinary::option_get($options, "html", array());

        $classes = array("cloudinary-fileupload");
        if (isset($html_options["class"])) {
            array_unshift($classes, Cloudinary::option_consume($html_options, "class"));
        }
        $tag_options = array_merge($html_options, array("type" => "file", "name" => "file",
            "data-url" => cl_upload_url($options),
            "data-form-data" => cl_upload_tag_params($options),
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
        $params = Cloudinary::sign_request($params, $options);

        $api_url = Cloudinary::cloudinary_api_url("upload", $options);

        $form = "<form enctype='multipart/form-data' action='" . $api_url . "' method='POST' " . Cloudinary::html_attrs($form_options) . ">\n";
        foreach ($params as $key => $value) {
            $form .= "<input " . Cloudinary::html_attrs(array("name" => $key, "value" => $value, "type" => "hidden")) . "/>\n";
        }
        $form .= "</form>\n";

        return $form;
    }
    
    // Examples
    // cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello") # W/H are not sent to cloudinary
    // cl_image_tag("israel.png", array("width"=>100, "height"=>100, "alt"=>"hello", "crop"=>"fit") # W/H are sent to cloudinary
    function cl_image_tag($source, $options = array()) {
        $source = cloudinary_url_internal($source, $options);
        if (isset($options["html_width"])) $options["width"] = Cloudinary::option_consume($options, "html_width");
        if (isset($options["html_height"])) $options["height"] = Cloudinary::option_consume($options, "html_height");

        $responsive = Cloudinary::option_consume($options, "responsive");
        $hidpi = Cloudinary::option_consume($options, "hidpi");
        if ($responsive || $hidpi) {
            $options["data-src"] = $source;
            $classes = array($responsive ? "cld-responsive" : "cld-hidpi");
            $current_class = Cloudinary::option_consume($options, "class");
            if ($current_class) array_unshift($classes, $current_class);
            $options["class"] = implode(" ", $classes);
            $source = Cloudinary::option_consume($options, "responsive_placeholder", Cloudinary::config_get("responsive_placeholder"));
            if ($source == "blank") {
                $source = Cloudinary::BLANK;
            }
        }
        $html = "<img ";
        if ($source) $html .= "src='$source' ";
        $html .= Cloudinary::html_attrs($options) . "/>";
        return $html;
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
        foreach (Cloudinary::$JS_CONFIG_PARAMS as $param) {
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
        if (!isset($options["secure"])) {
            $options["secure"] = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' )
                || ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' );
        }
    
        return Cloudinary::cloudinary_url($source, $options);
    }
    
    function cl_sprite_url($tag, $options = array()) {
        if (substr($tag, -strlen(".css")) != ".css") {
            $options["format"] = "css";
        }
        $options["type"] = "sprite";
        return cloudinary_url_internal($tag, $options);
    }
    
    function cl_sprite_tag($tag, $options = array()) {
        return "<link rel='stylesheet' type='text/css' href='" . cl_sprite_url($tag, $options) . "'>";
    }

}
