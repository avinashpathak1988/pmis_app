<?php
App::uses('AppModel', 'Model');
class ShiftDeployment extends AppModel {

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
		'shift_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=>'Select Shift'
			),
		),
		'force_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=>'Select Force Id'
			),
		),
		'shift_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=>'Select Date'
			),

		),

		'deploy_area' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=>'Enter Area of Deployment'
			),
		),
	);
	public $belongsTo = array(
		'Shift' => array(
			'className' => 'Shift',
			'foreignKey' => 'shift_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AreaOfDeployment' => array(
			'className' => 'AreaOfDeployment',
			'foreignKey' => 'deploy_area',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
}
