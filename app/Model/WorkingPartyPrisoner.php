<?php
App::uses('AppModel', 'Model');
class WorkingPartyPrisoner extends AppModel {
	public $validate = array(
		'assignment_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Assignment date is required.'
			),
		),	
		'start_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Start date is required.'
			),
		),	
		'end_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'End date is required.'
			),
		),	
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner number is required.'
			),
		),
	);
	public $belongsTo = array(
		'WorkingParty' => array(
			'className' => 'WorkingParty',
			'foreignKey' => 'working_party_id'
		),
		// 'Prisoner' => array(
		// 	'className' => 'Prisoner',
		// 	'foreignKey' => 'prisoner_id'
		// ),
	);
	public $hasMany = array(
		'WorkingPartyPrisonerApprove' => array(
			'className' => 'WorkingPartyPrisonerApprove',
			'foreignKey' => 'working_party_prisoner_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		);
}
