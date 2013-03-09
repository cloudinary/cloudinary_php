<?php
require 'main.php';
# Here should do whatever authentication / verification needed

# Check for authentic cloudinary response
$api_secret = Cloudinary::config_get("api_secret");
if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");
$existing_signature = \Cloudinary::option_consume($_POST, "signature");
$to_sign = array(
    'public_id' => $_POST['public_id'],
    'version' => $_POST['version'],
);
$calculated_signature = \Cloudinary::api_sign_request($to_sign, $api_secret);

if ($existing_signature == $calculated_signature) {
    # Create model using all the data received (best practice is to save locally
    # only data needed for reaching the image on cloudinary - public_id, version;
    # and fields that are needed for queries, display, etc (ie - url, width, height)
    $photo = \PhotoAlbum\create_photo_model($_POST);
} else {
    error_log("Given data's signature can't be verified (" .
        $existing_signature . " != " . $calculated_signature . "). data: " .
        \PhotoAlbum\ret_var_dump($_POST));
}
?>
