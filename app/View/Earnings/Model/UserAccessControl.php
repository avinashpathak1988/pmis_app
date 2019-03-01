<?php
App::uses('AppModel', 'Model');
class UserAccessControl extends AppModel {
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'prison_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prison Id is required.'
			),
		),
		'user_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'User type is required.'
			),
		),						
	);
	public function beforeSave($options = Array()) {
		unset($this->data['UserAccessControl']['prisonId']);
		unset($this->data['UserAccessControl']['userType']);
	}
}
