<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class PrisonerWard extends AppModel {
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
		'ward_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please Select Ward',
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
		)
	);
}
