<?php
include '../../src/Cloudinary.php';
include '../../src/Uploader.php';
if (file_exists('settings.php')) {
  include 'settings.php';
}

$sample_paths = array(
  "pizza" => getcwd(). "\\pizza.jpg",
  "lake" => getcwd(). "\\lake.jpg",
  "couple" => "http://res.cloudinary.com/demo/image/upload/couple.jpg",
);
$default_thumbs = array(
  "width" => 200,
  "height" => 150,
);
$default_upload_options = array("tags" => "basic_sample");
$eager_params = array_merge($default_thumbs, array("crop" => "scale"));
$files = array();

# This function, when called uploads all files into your Cloudinary storage and saves the
# metadata to the $files array.
function do_uploads() {
  global $files, $sample_paths, $default_thumbs, $default_upload_options, $eager_params;
  # public_id will be generated on Cloudinary backend.
  $files["unnamed_local"] = \Cloudinary\Uploader::upload($sample_paths["pizza"],
    $default_upload_options);
  # Same image, uploaded with a public_id
  $files["named_local"] = \Cloudinary\Uploader::upload($sample_paths["pizza"],
    array_merge($default_upload_options, array("public_id" => "named")));

  # Eager tranformations are applied as soon as the file is uploaded, instead of waiting
  # for a user to request them. 
  $files["eager"] = \Cloudinary\Uploader::upload($sample_paths["lake"],
    array_merge($default_upload_options, array(
      "public_id" => "eager",
      "eager" => $eager_params,
    )
  ));
  # In the two following examples, the file is fetched from a remote URL and stored in Cloudinary.
  # This allows you to apply the same transformations, and serve those using Cloudinary's CDN.
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
}

# Output an image in HTML along with provided caption and public_id
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
    <?php
      echo "<h2>Uploading ... </h2>";
      do_uploads();
      echo "<h3>... Done!</h3>";
    ?>
    <table style="text-align: center;">
      <tr>
        <?php
          show_image($files["unnamed_local"], array_merge($default_thumbs,
            array("crop" => "fill")), "local unnamed, crop - fill");
          show_image($files["named_local"], array_merge($default_thumbs, 
            array("crop" => "fit")), "local named, crop - fit");
          show_image($files["eager"], $eager_params, "local with eager tranformations");
          show_image($files["remote"], array_merge($default_thumbs, 
            array("crop" => "thumb", "gravity" => "faces")), "fetch remotely + crop gravity faces");
          show_image($files["remote_trans"], array_merge($default_thumbs, 
            array("crop" => "fill", "gravity" => "face", "radius" => 10)),
            "fetch remotely with effects");
        ?>
      </tr>
    </table>
  </body>
</html>
