<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class SectionOfLaw extends AppModel {
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
				'message' => 'Section Of Law is required ',
			),
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Section Of Law already exists !',
            ),
		),
	);
	public $belongsTo = array(
		'Offence' => array(
			'className' => 'Offence',
			'foreignKey' => 'offence_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
