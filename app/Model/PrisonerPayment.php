<?php
App::uses('AppModel', 'Model');
class PrisonerPayment extends AppModel {
	
	public $belongsTo = array(
		'Prison' => array(
			'className' => 'Prison',
			'foreignKey' => 'prison_id'
		),
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
	);
}
