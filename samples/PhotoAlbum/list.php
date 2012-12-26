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
                  echo cl_image_tag($photo["public_id"], array(
                    "format" => $photo["format"], "crop" => "fill", "height" => 100, "width" => 60))
                ?>
            </a>
          <div class="less_info">
            <a href="#" class="toggle_info">Show more...</a>
          </div>
          <div class="more_info">
            <a href="#" class="toggle_info">Show less...</a>
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
