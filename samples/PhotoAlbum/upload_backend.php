<?php
require 'main.php';

function create_photo($file_path) {
  $result = \Cloudinary\Uploader::upload($file_path, array(
    "tags" => "backend_photo_album",
  ));
  unlink($file_path);
  error_log("upload result: " . \PhotoAlbum\ret_var_dump($result));
  $photo = \PhotoAlbum\create_photo_model($result);
  return $result["public_id"];
}

$files = $_FILES["files"];
$files = is_array($files) ? $files : array($files);
$ids = array();
foreach ($files["tmp_name"] as $index => $value) {
  array_push($ids, create_photo($value));
}

?>
<html><head><title>Upload succeeded!</title></head>
  <body>
    <h1>Your photos has been uploaded</h1>
    <?php
      foreach ($ids as $id) {
        echo cl_image_tag($id, $thumbs_params);
      }
    ?>
  </body>
</html>
