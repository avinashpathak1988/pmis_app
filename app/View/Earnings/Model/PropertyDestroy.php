<?php
App::uses('AppModel', 'Model');
class PropertyDestroy extends AppModel {
	public $validate = array(
		'destroy_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Destroy date is required.'
			),
		),
		'destroy_cause' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Destroy cause is required.'
			),
		),		
	);
}
