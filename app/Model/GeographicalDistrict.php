<?php
App::uses('AppModel', 'Model');
/**
 * District Model
 *
 */
class GeographicalDistrict extends AppModel {
/**
 * Display field
 *
 * @var string
 */ public $useTable = 'geographical_districts';
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
				'message' => 'Geographical District name is required ',
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
		'PrisonDistrict' => array(
			'className' => 'PrisonDistrict',
			'foreignKey' => 'district_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	
}
