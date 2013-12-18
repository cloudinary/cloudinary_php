<?php
    $this->layout = 'photoalbum';
    # Include jQuery
    echo $this->Html->script('//code.jquery.com/jquery-1.10.1.min.js');
    # Include cloudinary_js dependencies (requires jQuery)
    echo $this->Form->cloudinary_includes();
    # Setup cloudinary_js using the current cloudinary_php configuration
    echo cloudinary_js_config();
?>

<!-- A standard form for sending the image data to your server -->
<div id='backend_upload'>
  <h1>Upload through your server</h1>
  <?php echo $this->Form->create('Photo', array('type' => 'file')); ?>
	<?php
    echo $this->Form->input('cloudinaryIdentifier', array("label" => "",
        "accept" => "image/gif, image/jpeg, image/png"));
	?>
	</fieldset>
  <?php echo $this->Form->end(__('Upload')); ?>
</div>


<!-- A form for direct uploading using a jQuery plug-in.
      The cl_image_upload_tag PHP function generates the required HTML and JavaScript to
      allow uploading directly frm the browser to your Cloudinary account -->
<div id='direct_upload'>
  <h1>Direct upload from the browser</h1>
  <?php
    echo $this->Form->create('Photo', array('type' => 'file'));
    # The callback URL is set to point to an HTML file on the local server which works-around restrictions
    # in older browsers (e.g., IE) which don't full support CORS.
    echo $this->Form->input('cloudinaryIdentifier', array("type" => "direct_upload",
        "label" => "", "cloudinary" => array("callback" => $cors_location, "html" => array(
            "multiple" => true, "accept" => "image/gif, image/jpeg, image/png"))));
    # A simple $this->Form->input('cloudinaryIdentifier', array("type" => "direct_upload"))
    # should be sufficient in most cases

    echo $this->Form->end();
  ?>

  <!-- status box -->
  <div class="status">
    <h2>Status</h2>
    <span class="status_value">Idle</span>
  </div>

  <div class="uploaded_info_holder">
  </div>
</div>

<?php echo $this->Html->link('Back to list...',
    array('controller' => 'photos', 'action' => 'list_photos'),
    array('class' => 'back_link'));
?>

<script>
  function prettydump(obj) {
    ret = ""
    $.each(obj, function(key, value) {
      ret += "<tr><td>" + key + "</td><td>" + value + "</td></tr>";
    });
    return ret;
  }

  $(function() {
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
        $('.status_value').text('Updating backend');
        $.post(this.form.action, $(this.form).serialize(), function () {
            $('.status_value').text('Idle');
        });
        var info = $('<div class="uploaded_info"/>');
        $(info).append($('<div class="data"/>').append(prettydump(data.result)));
        $(info).append($('<div class="image"/>').append(
          $.cloudinary.image(data.result.public_id, {
              format: data.result.format, width: 150, height: 150, crop: "fill"
          })
        ));
        $('.uploaded_info_holder').append(info);
    });
  });
</script>
