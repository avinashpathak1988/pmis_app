<?php
App::uses('AppModel', 'Model');
class Disease extends AppModel {
	public $validate = array(
		'name' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Disease is required !',
			),
		),	
	);
}
