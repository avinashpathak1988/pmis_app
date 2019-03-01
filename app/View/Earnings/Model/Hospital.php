<?php
App::uses('AppModel', 'Model');
class Hospital extends AppModel {
	public $validate = array(
		'name' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Hospital is required !',
			),
		),	
	);
}