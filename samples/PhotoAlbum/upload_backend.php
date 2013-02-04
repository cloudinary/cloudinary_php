<?php
require 'main.php';

function create_photo($file_path) {
  $result = \Cloudinary\Uploader::upload($file_path, array(
    "tags" => "backend_photo_album",
  ));
  unlink($file_path);
  error_log("upload result: " . \PhotoAlbum\ret_var_dump($result));
  $photo = \PhotoAlbum\create_photo_model($result);
  return $result;
}

$files = $_FILES["files"];
$files = is_array($files) ? $files : array($files);
$files_data = array();
foreach ($files["tmp_name"] as $index => $value) {
  array_push($files_data, create_photo($value));
}

?>
<html><head><title>Upload succeeded!</title></head>
  <body>
    <h1>Your photos has been uploaded</h1>
    <?php
      foreach ($files_data as $file_data) {
        echo "<table>";
        foreach ($file_data as $key => $value) {
          echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
        }
        echo "</table>";
        echo cl_image_tag($file_data['public_id'], $thumbs_params);
      }
    ?>
  </body>
</html>
