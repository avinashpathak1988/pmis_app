<?php
App::uses('AppModel', 'Model');
class Shift extends AppModel {

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
                'message'=>'Name is required !'
			),
		),
		'start_time' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
                'message'=>'Start time is required !'
			),
		),
		'end_time' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
                'message'=>'End time is required !'
			),
		),
	);
}
