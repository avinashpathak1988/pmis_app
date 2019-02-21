<?php
App::uses('AppModel', 'Model');
class Holiday extends AppModel {

	public $useTable = 'holidays';
	// public $validate = array(
	// 	'name' 	=> array(
	// 		'notBlank' 		=> array(
	// 			'rule' 		=> array('notBlank'),
	// 			'message' 	=> 'Hospital is required !',
	// 		),
	// 	),	
	// );
}