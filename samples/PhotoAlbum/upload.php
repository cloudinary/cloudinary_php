<?php
require 'main.php';
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>jQuery File Upload Example</title>
</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="lib/jquery.ui.widget.js"></script>
<script src="lib/jquery.iframe-transport.js"></script>
<script src="lib/jquery.fileupload.js"></script>
<script src="lib/jquery.cloudinary.js"></script>

<div id='backend_upload'>
    <br /><h1>Upload through backend</h1>
    <input id="fileupload" type="file" name="files[]" data-url="upload_backend.php" multiple>
</div>

<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        dropZone: '#backend_upload',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#backend_upload');
            });
        }
    });
});
</script>

<div id='direct_upload'>
    <br /><h1>Direct upload</h1>
    <?php
      echo cl_image_upload_tag('test', array("tags" => "direct_photo_album"));
    ?>
</div>
<script>
    $('.cloudinary-fileupload')
    .fileupload({ dropZone: '#direct_upload' })
    .on('cloudinarydone', function (e, data) {
        console.log("cloudinarydone: ", data);
        $.post('upload-complete', { public_id: data.result.public_id } );
        $('<p/>').text(data.result.public_id).appendTo('#direct_upload');
    });
</script>
</body> 
</html>

