<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class Menu extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Menu Name is required',
				'allowEmpty' => false,
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Menu Name already exists !',
				'on'=>'create',
			),
		),
		'order' 		=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Order is required !',
			),
		),
	);
}