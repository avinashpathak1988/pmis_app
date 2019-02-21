<?php
App::uses('AppModel', 'Model');
class MedicalRecord extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'check_up_date';
/**
 * Validation rules
 *
 * @var array
 */
	
	public $validate = array(
		'check_up_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
}
