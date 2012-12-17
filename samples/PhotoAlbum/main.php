<?php
namespace PhotoAlbum {
  require 'rb.php';
  require 'src/Cloudinary.php';
  require 'src/Uploader.php';

  // sets up cloudinary params and RB's DB
  include 'settings.php';

  function create_photo_model($public_id, $format) {
    $photo = \R::dispense('photo');
    $photo->public_id = $public_id;
    $photo->format = $format;
    #$photo->timestamp = R::isoDateTime();
    $id = \R::store($photo);
  }

  function ret_var_dump($var) {
    ob_start();
    var_dump($var);
    return ob_get_clean();
  }
}

?>
