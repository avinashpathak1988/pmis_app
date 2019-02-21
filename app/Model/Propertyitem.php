<?php
App::uses('AppModel', 'Model');
class Propertyitem extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Property Name is required.'
			),
		),
	);
}
