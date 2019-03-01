<?php
App::uses('AppModel', 'Model');
class WorkingPartyReject extends AppModel {
	
	public $belongsTo = array(
		// 'CurrentWorkingParty' => array(
		// 	'className' => 'WorkingParty',
		// 	'foreignKey' => 'current_working_party_id'
		// ),
		// 'TransferWorkingParty' => array(
		// 	'className' => 'WorkingParty',
		// 	'foreignKey' => 'transfer_working_party_id'
		// ),
		'WorkingPartyPrisoner' => array(
			'className' => 'WorkingPartyPrisoner',
			'foreignKey' => 'prev_assign_prisoner_id'
		),
	);
}
