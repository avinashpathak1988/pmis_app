<?php
App::uses('AppModel', 'Model');
class Earning extends AppModel {
	public $validate = array(
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Earning grade name is required.'
			),
		),	
	);
}
