<?php
App::uses('AppModel', 'Model');
/**
 * photo Model
 *
 */
class Photo extends AppModel {
    public $actsAs = array('CloudinaryCake.Cloudinary' => array('fields' => array('cloudinaryIdentifier')));
}
