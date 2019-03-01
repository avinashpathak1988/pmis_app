<?php
App::uses('AppModel', 'Model');
class Prisonerinout extends AppModel {
public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'gate_keeper_id',
        ),
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_no',
        ),
    );
public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Prisonerinout'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),

        'Gatepass'   => array(
            'className'     => 'Gatepass',
            'foreignKey'    => 'reference_id',
            'conditions'    => array('model_name' => 'Prisonerinout'),
            'order'         => 'Gatepass.created DESC',
            'limit'         => 1
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
