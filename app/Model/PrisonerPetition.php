<?php
App::uses('AppModel', 'Model');
class PrisonerPetition extends AppModel {
	
	public $belongsTo = array(
		
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
	);
}
