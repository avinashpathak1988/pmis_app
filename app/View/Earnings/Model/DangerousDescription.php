<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class DangerousDescription extends AppModel {
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
				'message' => array('Please Provide Dangerous Description')
			),
		),
	);
}
