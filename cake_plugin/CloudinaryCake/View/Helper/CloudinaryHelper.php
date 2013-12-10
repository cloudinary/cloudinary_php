<?php

App::uses('FormHelper', 'View/Helper');

class CloudinaryHelper extends FormHelper {
    public $helpers = array('Html');
    public $cloudinaryFunctions = array(
        "cl_image_tag",
        "fetch_image_tag",
        "facebook_profile_image_tag",
        "gravatar_profile_image_tag",
        "twitter_profile_image_tag",
        "twitter_name_profile_image_tag",
        "cloudinary_js_config",
        "cloudinary_url",
        "cl_sprite_url",
        "cl_sprite_tag",
        "cl_upload_url",
        "cl_upload_tag_params",
        "cl_image_upload_tag",
        "cl_form_tag"
    );
    public $cloudinaryJSIncludes = array(
        "jquery.ui.widget",
        "jquery.iframe-transport",
        "jquery.fileupload",
        "jquery.cloudinary",
    );

    public function __call($name, $args) {
        if (in_array($name, $this->cloudinaryFunctions)) {
            return call_user_func_array($name, $args);
        }
        return parent::__call($name, $args);
    }

    /// Automatically detect cloudinary fields on models that have declared them.
	public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);
        $model = $this->_getModel($this->model());
        $fieldKey = $this->field();
        if (!@$options['type'] && $model->hasMethod('cloudinaryFields') && in_array($fieldKey, $model->cloudinaryFields())) {
            $options['type'] = 'file';
        }
        return parent::input($fieldName, $options);
    }

    public function cloudinary_includes($options = array()) {
        foreach ($this->cloudinaryJSIncludes as $include) {
            echo $this->Html->script($include, $options);
        }
    }

    /// Called for input() when type => direct_upload
    public function direct_upload($fieldName, $options = array()) {
        $modelKey = $this->model();
        $fieldKey = $this->field();
        $options = @$options["cloudinary"] ? $options["cloudinary"] : array();
        return \cl_image_upload_tag("data[" . $modelKey . "][" . $fieldKey . "]", $options);
    }
}
