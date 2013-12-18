<?php
/**
 *
 * PHP 5
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>PhotoAlbum - <?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('favicon', cloudinary_url("http://cloudinary.com/favicon.png",
           array("type" => "fetch")), array('type' => 'icon'));
        echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');

		echo $this->Html->css('photoalbum');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
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
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</body>
</html>
