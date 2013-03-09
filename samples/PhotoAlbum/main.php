<?php
namespace PhotoAlbum {
  require 'lib/rb.php';
  require '../../src/Cloudinary.php';
  require '../../src/Uploader.php';
  error_reporting(E_ALL | E_STRICT);

  // sets up cloudinary params and RB's DB
  include 'settings.php';

  // global settings
  $cors_location = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] .
    dirname($_SERVER["SCRIPT_NAME"]) . "/lib/cloudinary_cors.html";
  $thumbs_params = array("format" => "jpg", "height" => 150, "width" => 150, 
    "class" => "thumbnail inline");

  // help functions
  function ret_var_dump($var) {
    ob_start();
    var_dump($var);
    return ob_get_clean();
  }

  function array_to_table($array) {
    $saved_error_reporting = error_reporting(0);
    echo "<table>";
    foreach ($array as $key => $value) {
      echo "<tr><td>" . $key . "</td><td>" . json_encode($value) . "</td></tr>";
    }
    echo "</table>";
    error_reporting($saved_error_reporting);
  }
}

?>
