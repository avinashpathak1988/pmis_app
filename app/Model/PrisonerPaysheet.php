<?php
App::uses('AppModel', 'Model');
class PrisonerPaysheet extends AppModel {
	public $validate = array(
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner number is required.'
			),
		),	
		'prisoner_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner name is required.'
			),
		),	
		'date_of_pay' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Date of pay is required.'
			),
		),	
		'amount' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Amount is required.'
			),
		),	
		'balance' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Balance is required.'
			),
		),	
		'checked_by_oc' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Checked by O/C is required.'
			),
		),
	);
	
	public $belongsTo = array(
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
	);
}
