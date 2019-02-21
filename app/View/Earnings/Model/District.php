<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class District extends AppModel {
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
				'message' => 'District name is required ',
			),
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'District already exists !',
            ),
		),
	);
	public $belongsTo = array(
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
