<?php
require 'main.php';
# Here should do whatever authentication / verification needed

# Create model using the data received
$photo = \PhotoAlbum\create_photo_model($_POST["public_id"], $_POST["format"]);
?>
