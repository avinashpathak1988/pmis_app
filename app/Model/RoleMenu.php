<?php
App::uses('AppModel', 'Model');
class RoleMenu extends AppModel {
	public $validate = array(
		'user_type_id' 		=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'User type is required !',
			),
		),	
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	// public $belongsTo = array(
	// 	'Menu' => array(
	// 		'className' => 'Menu',
	// 		'foreignKey' => 'menu_id',
	// 		'conditions' => '',
	// 		'fields' => '',
	// 		'order' => ''
	// 	)
	// );
}