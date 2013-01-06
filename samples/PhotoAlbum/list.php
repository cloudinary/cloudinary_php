<?php include 'main.php' ?>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <style>
      .more_info, .show_more_info .less_info {
        display: none;
      }
      .show_more_info .more_info, .less_info {
        display: block;
      }
      .inline {
        display: inline-block;
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
    <h1>Photos</h1>
    <div class="photos">
      <?php
        foreach (R::findAll('photo') as $photo) {
      ?>
        <div class="photo">
            <a href="<?php echo cloudinary_url($photo["public_id"], 
                array("format" => $photo["format"])) ?>" target="_blank">
                <?php 
                  echo cl_image_tag($photo["public_id"], array_merge($thumbs_params, array("crop" => "fill")));
                ?>
            </a>
          <div class="less_info">
            <a href="#" class="toggle_info">Show more...</a>
          </div>
          <div class="more_info">
            <a href="#" class="toggle_info">Show less...</a>
            <div class="thumbnails">
              Different thumbnails:
              <?php 
                $thumbs = array(
                  array("crop" => "fill", "radius" => 10),
                  array("crop" => "scale"),
                  array("crop" => "fit"),
                  array("crop" => "thumb", "gravity" => "face"),
                  array("crop" => "thumb", "gravity" => "face"),
                  array("crop" => "fill", "gravity" => "north", "format" => "png", "transformation" => array(
                    array("effect" => "sepia"),
                    array("angle" => "20"),
                  )),
                );
                foreach($thumbs as $params) {
                  echo cl_image_tag($photo["public_id"], array_merge($thumbs_params, $params));
                }
                /*
                echo cl_image_tag($photo["public_id"], array(
                  array_merge($thumbs_params, array("crop" => "fill", "gravity" => "north")),
                  array("angle" => "20"),
                  array("effect" => "sepia"),
                ));
                */
              ?>
            </div>
            <?php 
              foreach ($photo as $key => $value) {
                echo "<p>$key = $value</p>";
              }
            ?>
          </div>
        </div>
      <?php } ?>
    </div>
  </body>
</html>
