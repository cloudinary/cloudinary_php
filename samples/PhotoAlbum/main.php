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

  $thumbs_params = array("format" => "jpg", "height" => 150, "width" => 150, 
    "class" => "thumbnail inline");
}

?>
