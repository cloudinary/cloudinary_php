<?php
require 'main.php';
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>PhotoAlbum - Upload page</title>

	<link href="style.css" media="all" rel="stylesheet" />

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
      <!-- This will render the image fetched from a remote HTTP URL using Cloudinary -->
      <?php echo fetch_image_tag("http://cloudinary.com/images/logo.png") ?>
    </div>
    
    <div id="posterframe">
      <!-- This will render the fetched Facebook profile picture using Cloudinary according to the
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

    <!-- A standard form for sending the image data to your server -->
    <div id='backend_upload'>
      <h1>Upload through your server</h1>
      <form action="upload_backend.php" method="post" enctype="multipart/form-data">
        <input id="fileupload" type="file" name="files[]" multiple accept="image/gif, image/jpeg, image/png">
        <input type="submit" value="Upload">
      </form>
    </div>

    
    <!-- A form for direct uploading using a jQuery plug-in. 
          The cl_image_upload_tag PHP function generates the required HTML and JavaScript to
          allow uploading directly frm the browser to your Cloudinary account -->
    <div id='direct_upload'>
      <h1>Direct upload from the browser</h1>
      <form>
      <?php
        # The callback URL is set to point to an HTML file on the local server which works-around restrictions 
        # in older browsers (e.g., IE) which don't full support CORS.
        echo cl_image_upload_tag('test', array("tags" => "direct_photo_album", "callback" => $cors_location, "html" => array("multiple" => true)));
      ?>
      </form>

	  <!-- status box -->
	  <div class="status">
	    <h2>Status</h2>
	    <span class="status_value">Idle</span>
	  </div>
	
      <div class="uploaded_info_holder">
      </div>
    </div>

    <a href="list.php" class="back_link">Back to list...</a>
    
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
          $('.status_value').text('Uploading...');
        },
      })
      .on('cloudinarydone', function (e, data) {
          $('.status_value').text('Idle');
          $.post('upload_complete.php', data.result);
          var info = $('<div class="uploaded_info"/>');
          $(info).append($('<div class="data"/>').append(prettydump(data.result)));
          $(info).append($('<div class="image"/>').append(
          	$.cloudinary.image(data.result.public_id, {
            	format: data.result.format, width: 150, height: 150, crop: "fill"
          	})
          ));
          $('.uploaded_info_holder').append(info);
          
      });
    </script>
  </body> 
</html>

