<?php
App::uses('AppModel', 'Model');
class Stage extends AppModel {
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Stage name is required.'
			),
		),	
	);
}
