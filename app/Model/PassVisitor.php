<?php
App::uses('AppModel', 'Model');
class PassVisitor extends AppModel {
	public $belongsTo = array(
		'VisitorPass' => array(
			'className' => 'VisitorPass',
			'foreignKey' => 'pass_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Iddetail' => array(
			'className' => 'Iddetail',
			'foreignKey' => 'nat_id_type',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Relationship' => array(
			'className' => 'Relationship',
			'foreignKey' => 'relation',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),

	);
}
