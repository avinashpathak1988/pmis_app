<?php
App::uses('AppModel', 'Model');
class Lodger extends AppModel {

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
        // 'Currency' => array(
        //     'className'     => 'Currency',
        //     'foreignKey'    => 'pp_cash',
        // ),
        'Prison' => array(
            'className'     => 'Prison',
            'foreignKey'    => 'prison_id',
        ),

    );
	public $hasMany = array(
        'LodgerPrisonerItem' => array(
            'className'     => 'LodgerPrisonerItem',
            'foreignKey'    => 'lodger_id',
            'conditions' => array('is_trash' => 0)
        ),
        'LodgerPrisonerCashItem' => array(
            'className'     => 'LodgerPrisonerCashItem',
            'foreignKey'    => 'lodger_id',
            'conditions' => array('is_trash' => 0)
        ),
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Lodger'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
}
