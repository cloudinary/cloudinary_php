<?php
namespace PhotoAlbum {
  require 'rb.php';
  require 'src/Cloudinary.php';
  require 'src/Uploader.php';

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
}

?>
