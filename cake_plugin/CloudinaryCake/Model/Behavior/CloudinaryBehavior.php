<?php

App::uses('ModelBehavior', 'Model');

class CloudinaryBehavior extends ModelBehavior {
    public $settingsDefaults = array(
        "fields" => array(),
        "changeModelDefaults" => true
    );

    public function setup(Model $Model, $settings = array()) {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = $this->settingsDefaults;
        }
        $this->settings[$Model->alias] = array_merge(
            $this->settings[$Model->alias], (array)$settings
        );
        if ($this->settings[$Model->alias]['changeModelDefaults']) {
            $this->changeModelDefaults($Model);
        }
    }

    /// Callbacks ///
    public function afterFind(Model $Model, $results, $primary = false) {
        $fieldNames = $this->relevantFields($Model);
        if (!$fieldNames) {
            return $results;
        }

        foreach ($results as &$result) {
            foreach ($fieldNames as $fieldName) {
                $this->updateCloudinaryField($Model, $fieldName, $result);
            }
        }
        return $results;
    }

    public function beforeSave(Model $Model, $options = array()) {
        foreach ($this->relevantFields($Model, $options) as $fieldName) {
            $this->saveCloudinaryField($Model, $fieldName);
        }
        return true;
    }

    public function beforeValidate(Model $Model, $options = array()) {
        foreach ($this->relevantFields($Model, $options) as $fieldName) {
            $field = @$Model->data[$Model->alias][$fieldName];
            if (is_string($field) && $field) {
                if (!(new CloudinaryField($field))->verify()) {
                    $Model->invalidate($fieldName, "Bad cloudinary signature!");
                    return false;
                }
            }
        }
        return true;
    }

    /// Public Methods ///
    public function cloudinaryFields(Model $Model) {
        return $this->settings[$Model->alias]['fields'];
    }

    /// Private Methods ///
    private function createCloudinaryField(Model $Model, $fieldName, $source=NULL) {
        $source = $source ? $source : $Model->data;
        return new CloudinaryField(isset($source[$Model->alias][$fieldName]) ?
            $source[$Model->alias][$fieldName] : "");
    }

    private function updateCloudinaryField(Model $Model, $fieldName, &$data=NULL) {
        $source =& $data ? $data : $Model->data;
        if (isset($source[$Model->alias][$fieldName]) && $source[$Model->alias][$fieldName] instanceof CloudinaryField) {
            return;
        }
        $source[$Model->alias][$fieldName] = $this->createCloudinaryField($Model, $fieldName, $source);
    }

    private function saveCloudinaryField(Model $Model, $fieldName) {
        $field = @$Model->data[$Model->alias][$fieldName];
        $ret = NULL;
        if ($field instanceof CloudinaryField) {
            return;
        } elseif (!$field) {
            $ret = new CloudinaryField();
        } elseif (is_string($field)) {
            $ret = new CloudinaryField($field);
            // $ret->verify(); - Validate only in beforeValidate
        } elseif (is_array($field) && isset($field['tmp_name'])) {
            $ret = new CloudinaryField();
            $ret->upload($field['tmp_name']);
        } else {
            // TODO - handle file object?
            throw new \Exception("Couldn't save cloudinary field '" . $Model->alias . ":" . $fieldName .
                "' - unknown input: " . gettype($field));
        }
        $Model->data[$Model->alias][$fieldName] = $ret;
    }

    private function relevantFields(Model $Model, $options = array()) {
        $cloudinaryFields = $this->settings[$Model->alias]['fields'];
        if (!(isset($options['fieldList']) && $options['fieldList'])) {
            return $cloudinaryFields;
        }
        return array_intersect($cloudinaryFields, $options['fieldList']);
    }

    private static function modifyPropertyUsingForce($instance, $property, $newValue) {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $myClassReflection = new ReflectionClass(get_class($instance));
            $secret = $myClassReflection->getProperty($property);
            $secret->setAccessible(true);
            $secret->setValue($instance, $newValue);
        }
    }

    private function changeModelDefaults(Model $Model) {
        $schema = $Model->schema();
        foreach ($this->relevantFields($Model) as $fieldName) {
            $schema[$fieldName] = new CloneOnAccessArray($schema[$fieldName]);
            $schema[$fieldName]['default'] = new CloudinaryField();
        }
        self::modifyPropertyUsingForce($Model, "_schema", $schema);
    }
}

class CloneOnAccessArray extends ArrayObject {
    public function offsetGet($offset) {
        $ret = parent::offsetGet($offset);
        return (is_object($ret)) ? clone $ret : $ret;
    }
}

/*
Simplest usage:
  Model:
    class Photo extends AppModel {
      public $actsAs = array('CloudinaryCake.Cloudinary' => array('fields' => array('cloudinaryIdentifier')));
    }

  Controller:
    Find:
      $photo = $this->Photo->find('first', $options); // returns CloudinaryField in

    Modify:
      $photo['Photo']['cloudinaryIdentifier'].upload($new_file);

    Delete:
      $photo['Photo']['cloudinaryIdentifier'].delete($new_file);

    Save:
      Photo->save($photo);

    Save (from form):
      Photo->save($this->request->data); // should work with file upload or identifier

    * Validate identifier upon save
    *
*/
