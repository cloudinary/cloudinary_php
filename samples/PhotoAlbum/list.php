<?php require 'main.php' ?>
<html>
  <head>
    <meta charset="utf-8">
    <title>PhotoAlbum - Main page</title>

    <link rel="shortcut icon"
     href="<?php echo cloudinary_url("http://cloudinary.com/favicon.png",
           array("type" => "fetch")); ?>" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <style>
      .more_info, .show_more_info .less_info {
        display: none;
      }
      .show_more_info .more_info, .less_info {
        display: inline-block;
      }
      .inline {
        display: inline-block;
      }
      td {
        vertical-align: top;
        padding-right: 5px;
      }
      .photo a {
        display: inline-block;
        width: 200px;
      }
      .photo > * {
        vertical-align: top;
      }
    </style>
    <script type='text/javascript'>
      $(function () {
        $('.toggle_info').click(function () {
          $(this).closest('.photo').toggleClass('show_more_info');
          return false;
        });
      });
    </script>
  </head>
  <body>
    <div id="logo">
        <!-- This will render the fetched image using cloudinary -->
        <?php echo fetch_image_tag("http://cloudinary.com/images/logo.png") ?>
    </div>
    <div id="posterframe" style="position: absolute; right: 0; top: 5px;">
        <!-- This will render the fetched facebook image using cloudinary with all the
             requested transformations -->
        <?php echo facebook_profile_image_tag("officialchucknorrispage", array(
          "format" => "png",
          "transformation" => array(
            array("height" => 95, "width" => 95, "crop" => "thumb", "gravity" => "face",
              "effect" => "sepia", "radius" => 20
            ), array("angle" => 10)
          ))); 
        ?>
    </div>
    <h1>Welcome!</h1>
    <p>This is the main demo page of the PhotoAlbum sample PHP application for Cloudinary.<br />
    Here you can see all the photos you had uploaded to the app and find some information about how
    to implement your own PHP application storing, manipulating and serving your photos using 
    Cloudinary!</p>

    <p>All of the images you see here are served by cloudinary. For instance the logo and the posterframe. They are both render from server side using the Cloudinary shortcut functions: fetch_image_tag and facebook_profile_image_tag. These two pictures weren't even have to be uploaded to cloudinary, they are retrieved by the backend, transformed, cached and distributed using a CDN.
    </p>

    <h1>Photos</h1>
    <div class="photos">
      <?php if (R::count('photo') > 0) { ?>
        <p>Following are the photos uploaded by you. If you want to upload more pictures go to the
        <a href="upload.php">upload page</a>.<br />
        You can click each picture to view its original size, and see more info about it by clicking
        <i>show more...</i></p>
      <?php } else { ?>
        <p>No images were uploaded yet. click <a href="upload.php">here</a>
         to go to the upload page</p>
      <?php
        }
        $index = 0;
        foreach (R::findAll('photo') as $photo) {
      ?>
        <div class="photo<?php echo $index == 1 ? " show_more_info" : "" ?>">
            <a href="<?php echo cloudinary_url($photo["public_id"], 
                array("format" => $photo["format"])) ?>" target="_blank">
                <?php 
                  echo $photo["public_id"];
                  // echo cl_image_tag($photo["public_id"], array_merge($thumbs_params, array("crop" => "fill")));
                ?>
            </a>
          <div class="less_info">
            <a href="#" class="toggle_info">Show more...</a>
          </div>
          <div class="more_info">
            <a href="#" class="toggle_info">Show less...</a>
            <table class="thumbnails">
              Different thumbnails:
              <?php 
                $thumbs = array(
                  array("crop" => "fill", "radius" => 10),
                  array("crop" => "scale"),
                  array("crop" => "fit"),
                  array("crop" => "thumb", "gravity" => "face"),
                  array("crop" => "thumb", "gravity" => "face"),
                  array("override" => true, "format" => "png", "transformation" => array(
                    array("crop" => "fill", "gravity" => "north", "width" => 150, "height" => 150),
                    array("effect" => "sepia"),
                    array("angle" => "20"),
                  )),
                );
                foreach($thumbs as $params) {
                  $merged_params = array_merge((\Cloudinary::option_consume($params, "override")) ? array() : $thumbs_params, $params);
                  echo "<td>";
                  echo cl_image_tag($photo["public_id"], $merged_params);
                  echo "<br/>";
                  \PhotoAlbum\array_to_table($merged_params);
                  echo "</td>";
                }
              ?>
            </table>
            <p> As you can see, different tranformations can go a long way.
            You're welcome to consult the <a href="http://cloudinary.com/documentation/image_transformations"
            >Image Transformation reference</a> for more awesome transformations
          </div>
        </div>
      <?php $index++; } ?>
    </div>
  </body>
</html>
