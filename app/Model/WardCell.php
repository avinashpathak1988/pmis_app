<?php
App::uses('AppModel', 'Model');
/**
 * WardCell Model
 *
 */
class WardCell extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'cell_name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Cell name is required ',
			),
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Cell already exists !',
            ),
		),
	);
	public $belongsTo = array(
		'Ward' => array(
			'className' => 'Ward',
			'foreignKey' => 'ward_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
