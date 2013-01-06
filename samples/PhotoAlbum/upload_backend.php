<?php
require 'main.php';

error_reporting(E_ALL | E_STRICT);
require('lib/UploadHandler.php');
class PhotoAlbumUploadHandler extends UploadHandler {
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index, $content_range);
        $this->create_photo($file);
        return $file;
    }

    protected function create_photo($file) {
      $file_path = $this->get_upload_path($file->name);
      $result = \Cloudinary\Uploader::upload($file_path, array(
        "tags" => "backend_photo_album",
      ));
      unlink($file_path);
      error_log("upload result: " . \PhotoAlbum\ret_var_dump($result));
      $photo = \PhotoAlbum\create_photo_model($result);
    }
}

$upload_handler = new PhotoAlbumUploadHandler(array(
  "image_versions" => array(),
));

?>
