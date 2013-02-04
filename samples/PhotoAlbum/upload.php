<?php
require 'main.php';
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>PhotoAlbum - Upload page</title>

    <link rel="shortcut icon"
     href="<?php echo cloudinary_url("http://cloudinary.com/favicon.png",
           array("type" => "fetch")); ?>" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="lib/jquery.ui.widget.js"></script>
    <script src="lib/jquery.iframe-transport.js"></script>
    <script src="lib/jquery.fileupload.js"></script>
    <script src="lib/jquery.cloudinary.js"></script>
    <script>
      $.cloudinary.config("cloud_name", "<?php echo Cloudinary::config_get("cloud_name"); ?>");
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

    <!-- This is the backend upload - based on the fileupload javascript for the frontend and also
         on the PHP backend demo. The uploaded images will be processed in upload_backend.php -->
    <div id='backend_upload'>
      <br /><h1>Upload through backend</h1>
      <form action="upload_backend.php" method="post" enctype="multipart/form-data">
        <input id="fileupload" type="file" name="files[]" multiple accept="image/gif, image/jpeg, image/png">
        <input type="submit">
      </form>
    </div>

    <!-- Status box -->
    <div class="status" style="position: fixed; width: 200px; left: 50%; text-align: center; border: double;">
      <h3>Status</h3>
      <span class="status_value">Idle</span>
    </div>
    <!-- This is the direct upload - also based on the fileupload for the frontend. 
          The cl_image_upload_tag php function is generating the required html and javascript to
          allow uploading directly to your Cloudinary storage -->
    <div id='direct_upload'>
      <br /><h1>Direct upload</h1>
      <?php
        echo cl_image_upload_tag('test', array("tags" => "direct_photo_album", "callback" => $cors_location));
      ?>
      <div class="uploaded-info" style="display: none;">
        <div class="data"></div>
        <div class="image"></div>
      </div>
    </div>
    <script>
      function prettydump(obj) {
        ret = ""
        $.each(obj, function(key, value) {
          ret += "<tr><td>" + key + "</td><td>" + value + "</td></tr>";
        });
        return ret;
      }
      $('.cloudinary-fileupload')
      .fileupload({ 
        dropZone: '#direct_upload',
        start: function () {
          $('.status_value').text('Starting direct upload...');
        },
        progress: function () {
          $('.status_value').text('Continue direct upload...');
        },
      })
      .on('cloudinarydone', function (e, data) {
          $('.status_value').text('Idle');
          $.post('upload_complete.php', data.result);
          $('.uploaded-info .data').html(prettydump(data.result));
          $('.uploaded-info .image').empty().append($.cloudinary.image(data.result.public_id, {
            format: "png", width: 400, height: 300,
          }));
          $('.uploaded-info').show();
      });
    </script>
  </body> 
</html>

