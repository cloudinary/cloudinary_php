Cloudinary CakePHP plugin
=========================

Cloudinary CakePHP plugin provides seemless integration of Cloudinary services with CakePHP framework for simple and efficient management of applications images

Explore the [PhotoAlbumCake sample](https://github.com/cloudinary/cloudinary_php/tree/master/samples/PhotoAlbumCake) for usage example.

## Requirements
* PHP 5.3 or higher
* CakePHP 2.x

## Installlation
### Manual
1. Create a CakePHP project
1. Download cloudinary\_php from [here](https://github.com/cloudinary/cloudinary_php/tarball/master)
1. Extract the cloudinary\_php archive into `vendors` library
1. Configure cloudinary
    1. Environment variable - `export CLOUDINARY\_URL = "cloudinary://API_KEY:API_SECRET@CLOUD_NAME"` ([Check your settings in Cloudinary console](https://cloudinary.com/console))
    1. Create `app/Config/CloudinaryPrivate.php` using `vendors/cloudinary_php/samples/PhotoAlbumCake/Config/CloudinaryPrivate.php.sample`
1. Load the cloudinary plugin by adding the following lines to `app/Config/bootstrap.php`:

        // Load plugin
        CakePlugin::load('CloudinaryCake', array('bootstrap' => true, 'routes' => false,
            'path' => ROOT . DS 'vendors' . DS 'cloudinary_php' . DS . 'cake_plugin' . DS . 'CloudinaryCake' . DS));

        // required when using `CloudinaryPrivate.php` for cloudinary configuration
        Configure::load('CloudinaryPrivate');
        \Cloudinary::config(Configure::read('cloudinary'));

### Composer
1. Create a new directory for myapp

        mkdir myapp
        cd myapp

1. Install CakePHP using composer ([based on CakePHP Cookbook](http://book.cakephp.org/2.0/en/installation/advanced-installation.html#installing-cakephp-with-composer)
    1. Setup Composer and get CakePHP:

            echo '{}' > composer.json
            composer config vendor-dir Vendor
            composer config repositories.0 pear 'http://pear.cakephp.org'
            composer require 'pear-cakephp/cakephp:>=2.4.0'

    1. Bake a new project

            Vendor/bin/cake bake project .

    1. You may define `CAKE_CORE_INCLUDE_PATH` to a relative path as suggested in the cookbook by adding the following to `webroot/index.php`:

            define(
                'CAKE_CORE_INCLUDE_PATH',
                ROOT . DS . APP_DIR . '/Vendor/pear-pear.cakephp.org/CakePHP'
            );

    1. Add the following lines to `Config/bootstrap.php`:

            // Load composer autoload.
            require APP . '/Vendor/autoload.php';

            // Auto load CloudinaryCake plugin
            \CloudinaryCakeLoader::load();


1. Install Cloudinary

        composer require 'cloudinary/cloudinary_php:>=1.0.8'

1. Configure Cloudinary using the `CLOUDINARY_URL` environment variable, or the `Config/CloudinaryPrivate.php` configuration file

## Usage

### CloudinaryBehavior
CloudinaryBehavior adds Cloudinary support for CakePHP Models. It helps storing references to cloudinary images in a simple text field of your model.

#### Setup
Assuming you have a `Photo` model with `cloudinaryIdentifier` text field for storing cloudinary images references - you can add the following code to your model

`Models/photo.php`:

    [...]
    class Photo extends AppModel {
        public $actsAs = array('CloudinaryCake.Cloudinary' => array('fields' => array('cloudinaryIdentifier')));
        [...]
    }

#### Usage
This will allow you to access the `cloudinaryIdentifier` as a CloudinaryField. Here's a sample controller code -

`Controller/PhotosController.php`:

    class PhotosController extends AppController {
        [...]
        // set the specified Photo's image to the default one
        public function set_default_image($id) {
            $options = array('conditions' => array('Photo.' . $this->Photo->primaryKey => $id));
            $photo = $this->Photo->find('first', $options);

            $photo['Photo']['cloudinaryIdentifier']->upload(DEFAULT_IMAGE_PATH);
            $this->Photo->save($photo);
        }

        [...]
        // Creates a new image from post data. Sets $image_url to the cloudinary url of the image with the given transformation.
        public function add() {
            $this->Photo->create();
            $success = $this->Photo->save($this->request->data);
            if ($success) {
                $image_url = $this->Photo->data['Photo']['cloudinaryIdentifier']->url(array(
                    "width" => 100, "height" => 100, "crop" => "fill"));
            }
		    $this->set('photo', $this->Photo->data);
        }
        [...]
    }

### CloudinaryHelper
CloudinaryHelper is an extension of the CakePHP InputHelper. It can be used for loading cloudinary\_js, presenting images, creating forms with image inputs and more.

#### Setup
You can load CloudinaryHelper using two methods -

`Controller/PhotosController.php`:

    [...]
    class PhotosController extends AppController {
        // Replace the FormHelper with CloudinaryHelper (recommended - accessible as $this->Form)
        public $helpers = array('Html', 'Form' => array('className' => 'CloudinaryCake.Cloudinary'));

        // Add CloudinaryHelper in addition to the default FormHelper (accessible as $this->Cloudinary instead of $this->Form)
        //public $helpers = array('Html', 'Form', 'CloudinaryCake.Cloudinary');
        [...]
    }

#### Usage
You then can use it in any view of the controller:

`View/Layouts/default.ctp`:

    [...]
    <head>
        [...]
        # Include cloudinary_js dependencies (requires jQuery)
        echo $this->Form->cloudinary_includes();
        # Setup cloudinary_js using the current cloudinary_php configuration
        echo cloudinary_js_config();
        [...]
    </head>
    [...]

`View/Photos/add.ctp`:

    [...]
        <span><?php echo __('Current Photo:'); ?></span>
        <?php echo $this->Form->cl_image_tag($photo['Photo']['cloudinaryIdentifier'],
            array("width" => 60, "height" => 60, "crop" => "thumb", "gravity" => "face")); ?>

        <?php echo $this->Form->create('Photo', array('type' => 'file')); ?>
            <legend><?php echo __('Edit Photo'); ?></legend>
            <?php
                echo $this->Form->input('id');
                # Backend upload:
                echo $this->Form->input('cloudinaryIdentifier');
                # Direct upload:
                #echo $this->Form->input('cloudinaryIdentifier', array("type" => "direct_upload"));
            ?>
        <?php echo $this->Form->end(__('Submit')); ?>
    [...]

