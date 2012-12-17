<?php
require 'main.php';

error_reporting(E_ALL | E_STRICT);
require('jQuery-File-Upload/server/php/UploadHandler.php');
class PhotoAlbumUploadHandler extends UploadHandler {
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index, $content_range);
        ob_start();
        var_dump($file);
        error_log("GOT File: " . ob_get_clean());
        $this->create_photo($file);
        return $file;
    }

    protected function create_photo($file) {
      $photo = R::dispense('photo');
      #$photo->public_id = 'sample';
      $photo->format = 'jpg';
      $photo->timestamp = R::isoDateTime();
      #var_dump($photo);
      $id = R::store($photo);


      # $this->get_upload_path($file->name);
      $file_path = $this->get_upload_path($file->name);
      $result = \Cloudinary\Uploader::upload($file_path, array(
        "tags" => "backend_photo_album",
      ));
      unlink($file_path);
    }
}
$upload_handler = new PhotoAlbumUploadHandler(array(
  "image_versions" => array(),
));

?>
