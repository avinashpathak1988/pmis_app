<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class EscortTeam extends AppModel {
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
				'message' => 'Team name is required ',
			),
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Team already exists !',
            ),
		),
	);
	public $belongsTo = array(
		'Prison' => array(
			'className' => 'Prison',
			'foreignKey' => 'prison_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
