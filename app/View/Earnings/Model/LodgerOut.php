<?php
App::uses('AppModel', 'Model');
class LodgerOut extends AppModel {

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
	public $validate = array(
		'date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

    public $belongsTo = array(
        'Lodger' => array(
            'className'     => 'Lodger',
            'foreignKey'    => 'lodger_id',
        ),
        'Prison' => array(
            'className'     => 'Prison',
            'foreignKey'    => 'prison_id',
        ),

    );
	public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'LodgerOut'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'Gatepass'   => array(
            'className'     => 'Gatepass',
            'foreignKey'    => 'reference_id',
            'conditions'    => array('model_name' => 'LodgerOut'),
            'order'         => 'Gatepass.created DESC',
            'limit'         => 1
        ),
    );
}
