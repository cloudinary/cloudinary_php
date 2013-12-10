<?php
$this->layout = 'photoalbum';
$thumbs_params = array("format" => "jpg", "height" => 150, "width" => 150,
    "class" => "thumbnail inline");

function array_to_table($array) {
    $saved_error_reporting = error_reporting(0);
    echo "<table class='info'>";
    foreach ($array as $key => $value) {
        if ($key != 'class') {
            if ($key == 'url' || $key == 'secure_url') {
                $display_value = '"' . $value . '"';
            } else {
                $display_value = json_encode($value);
            }
            echo "<tr><td>" . $key . ":</td><td>" . $display_value . "</td></tr>";
        }
    }
    echo "</table>";
    error_reporting($saved_error_reporting);
}

?>
<script type='text/javascript'>
  $(function () {
    $('.toggle_info').click(function () {
      $(this).closest('.photo').toggleClass('show_more_info');
      return false;
    });
  });
</script>
<h1>Welcome!</h1>

<p>
    This is the main demo page of the PhotoAlbum sample PHP application of Cloudinary.<br />
    Here you can see all images you have uploaded to this PHP application and find some information on how
    to implement your own PHP application storing, manipulating and serving your photos using Cloudinary!
</p>

<p>
    All of the images you see here are transformed and served by Cloudinary.
    For instance, the logo and the poster frame.
    They are both generated in the cloud using the Cloudinary shortcut functions: fetch_image_tag and facebook_profile_image_tag.
    These two pictures weren't even have to be uploaded to Cloudinary, they are retrieved by the service, transformed, cached and distributed through a CDN.
</p>

<h1>Your Images</h1>
<div class="photos">
  <p>
    Following are the images uploaded by you. You can also upload more pictures.

    You can click on each picture to view its original size, and see more info about and additional transformations.
    <?php echo $this->Html->link('Upload Images...',
        array('controller' => 'photos', 'action' => 'upload'),
        array('class' => 'upload_link'));
    ?>
  </p>
  <?php if (sizeof($photos) == 0) { ?>
    <p>No images were uploaded yet.</p>
  <?php
    }
    foreach ($photos as $photo) {
  ?>
	<div class="photo">
        <a href="<?php echo cloudinary_url($photo["Photo"]["cloudinaryIdentifier"]) ?>" target="_blank" class="public_id_link">
            <?php
              echo "<div class='public_id'>" . $photo["Photo"]["cloudinaryIdentifier"] . "</div>";
              echo cl_image_tag($photo["Photo"]["cloudinaryIdentifier"], array_merge($thumbs_params, array("crop" => "fill")));
            ?>
        </a>

      <div class="less_info">
        <a href="#" class="toggle_info">More transformations...</a>
      </div>

      <div class="more_info">
        <a href="#" class="toggle_info">Hide transformations...</a>
        <table class="thumbnails">
          <?php
            $thumbs = array(
              array("crop" => "fill", "radius" => 10),
              array("crop" => "scale"),
              array("crop" => "fit", "format" => "png"),
              array("crop" => "thumb", "gravity" => "face"),
              array("override" => true, "format" => "png", "angle" => 20, "transformation" =>
                array("crop" => "fill", "gravity" => "north", "width" => 150, "height" => 150, "effect" => "sepia")
              ),
            );
            foreach($thumbs as $params) {
              $merged_params = array_merge((\Cloudinary::option_consume($params, "override")) ? array() : $thumbs_params, $params);
              echo "<td>";
              echo "<div class='thumbnail_holder'>";
              echo "<a target='_blank' href='" . cloudinary_url($photo["Photo"]["cloudinaryIdentifier"], $merged_params) . "'>" .
                cl_image_tag($photo["Photo"]["cloudinaryIdentifier"], $merged_params) . "</a>";
              echo "</div>";
              echo "<br/>";
              array_to_table($merged_params);
              echo "</td>";
            }
          ?>

        </table>

        <div class="note">
            Take a look at our documentation of <a href="http://cloudinary.com/documentation/image_transformations" target="_blank">Image Transformations</a> for a full list of supported transformations.
        </div>
      </div>
    </div>
  <?php } ?>
</div>
