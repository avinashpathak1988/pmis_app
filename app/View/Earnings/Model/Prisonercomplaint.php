<?php
App::uses('AppModel', 'Model');
class Prisonercomplaint extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'date';
/**
 * Validation rules
 *
 * @var array
 */
public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Prisonercomplaint'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
	public $validate = array(
		'date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please select date',
			),
		),
		'prisoner_no' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please select Prisoner Number',
			),
		),
		'complaint' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter complaint',
			),
		),
	);
}
