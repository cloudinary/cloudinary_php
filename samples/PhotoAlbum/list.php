<?php include 'main.php'; ?>
<html>
  <body>
    <h1>Photos</h1>
    <div class="photos">
      <?php
        foreach (R::findAll('photo') as $photo) {
      ?>
        <div class="photo">
          <?php echo cl_image_tag($photo["public_id"], 
            array("format" => $photo["format"]))
          ?>
          <div class="show_more">+</div>
        </div>
      <?php } ?>
    </div>
  </body>
</html>
