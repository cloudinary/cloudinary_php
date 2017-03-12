<?php
require 'main.php';
# You can add here your custom verification code

# Check for a valid Cloudinary response
$api_secret = \Cloudinary\Cloudinary::configGet("api_secret");
if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");
$existing_signature = \Cloudinary\Cloudinary::optionConsume($_POST, "signature");
$to_sign = array(
    'public_id' => $_POST['public_id'],
    'version' => $_POST['version'],
);
$calculated_signature = \Cloudinary\Cloudinary::apiSignRequest($to_sign, $api_secret);

if ($existing_signature == $calculated_signature) {
    # Create a model record using the data received (best practice is to save locally
    # only data needed for reaching the image on Cloudinary - public_id and version;
    # and fields that might be needed for your application (e.g.,), width, height)
    $photo = \PhotoAlbum\create_photo_model($_POST);
} else {
    error_log("Received signature verficiation failed (" .
        $existing_signature . " != " . $calculated_signature . "). data: " .
        \PhotoAlbum\ret_var_dump($_POST));
}
