<?php
namespace PhotoAlbum {
  require 'lib/rb.php';
  require '../../src/Cloudinary.php';
  require '../../src/Uploader.php';
  error_reporting(E_ALL | E_STRICT);

  // sets up cloudinary params and RB's DB
  include 'settings.php';

  function create_photo_model($options = array()) {
    $photo = \R::dispense('photo');

    # Add metadata we want to keep:
    $photo->created_at = \R::isoDateTime();

    foreach ( $options as $key => $value ) {
        $photo->{$key} = $value;
    }
    
    $id = \R::store($photo);
  }

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

  $thumbs_params = array("format" => "jpg", "height" => 150, "width" => 150, 
    "class" => "thumbnail inline");
}

?>