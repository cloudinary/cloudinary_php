<?php
if (file_exists('settings.php')) {
  include 'settings.php';
}
include 'src/Uploader.php';

$sample_paths = array(
  "pizza" => getcwd(). "\\pizza.jpg",
  "lake" => getcwd(). "\\lake.jpg",
  "couple" => "http://res.cloudinary.com/demo/image/upload/couple.jpg",
);
$default_thumbs = array(
  "width" => 50,
  "height" => 80,
);
$default_upload_options = array("tags" => "basic_sample");
$files = array();
$files["unnamed_local"] = \Cloudinary\Uploader::upload($sample_paths["pizza"],
  $default_upload_options);
$files["named_local"] = \Cloudinary\Uploader::upload($sample_paths["pizza"],
  array_merge($default_upload_options, array("public_id" => "named")));
$eager_params = array_merge($default_thumbs, array("crop" => "scale"));
$files["eager"] = \Cloudinary\Uploader::upload($sample_paths["lake"],
  array_merge($default_upload_options, array(
    "public_id" => "eager",
    "eager" => $eager_params,
  )
));
$files["remote"] = \Cloudinary\Uploader::upload($sample_paths["couple"],
  $default_upload_options);
$files["remote_trans"] = \Cloudinary\Uploader::upload($sample_paths["couple"],
  array_merge($default_upload_options, array(
    "public_id" => "transformed",
    "width" => 500,
    "height" => 500,
    "crop" => "fit",
    "effect" => "saturation:-70",
  ))
);
function show_image($img, $options = array(), $caption = "") {
    echo "<td><div class='caption'>" . $caption . "</div>";
    $options["format"] = $img["format"];
    echo "<a href=" . $img["url"] . " target='_blank'>" . 
      cl_image_tag($img["public_id"], $options) . "</a>";
    echo "<div>" . $img["public_id"] . "</div>";
    echo "</td>";
}
?>
<html>
  <body>
    <table style="text-align: center;">
      <tr>
        <?php
          show_image($files["unnamed_local"], array_merge($default_thumbs,
            array("crop" => "fill")), "unnamed");
          show_image($files["named_local"], array_merge($default_thumbs, 
            array("crop" => "fit")), "named");
          show_image($files["eager"], $eager_params, "eager");
          show_image($files["remote"], array_merge($default_thumbs, 
            array("crop" => "thumb", "gravity" => "face")), "remote face");
          show_image($files["remote_trans"], array_merge($default_thumbs, 
            array("crop" => "fill", "gravity" => "face", "radius" => 10)),
            "remote with effect");
        ?>
      </tr>
    </table>
  </body>
</html>
