<?php
App::uses('AppModel', 'Model');
class Personinout extends AppModel {
public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'gate_keeper_id',
        ),
        
    );
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
			),
		),
	);
}
