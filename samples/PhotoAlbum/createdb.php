<?php
include 'main.php';

function create_photo() {
  $photo = R::dispense('photo');
  $photo->public_id = 'sample';
  $photo->format = 'jpg';
  $photo->timestamp = R::isoDateTime();
  #var_dump($photo);
  $id = R::store($photo);
  #var_dump($id);
  return $id;
};

echo "ID: " . create_photo();

?>
