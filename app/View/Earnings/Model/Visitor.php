<?php
App::uses('AppModel', 'Model');
class Visitor extends AppModel {

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
        'Currency' => array(
            'className'     => 'Currency',
            'foreignKey'    => 'pp_cash',
        ),
        'Prison' => array(
            'className'     => 'Prison',
            'foreignKey'    => 'prison_id',
        ),

    );
	public $hasMany = array(
        'VisitorItem' => array(
            'className'     => 'VisitorItem',
            'foreignKey'    => 'visitor_id',
            'conditions' => array('is_trash' => 0)
        ),
        'VisitorPrisonerItem' => array(
            'className'     => 'VisitorPrisonerItem',
            'foreignKey'    => 'visitor_id',
            'conditions' => array('is_trash' => 0)
        ),
        'VisitorPrisonerCashItem' => array(
            'className'     => 'VisitorPrisonerCashItem',
            'foreignKey'    => 'visitor_id',
            'conditions' => array('is_trash' => 0)
        ),
        'CanteenFoodItem' => array(
            'className'     => 'CanteenFoodItem',
            'foreignKey'    => 'visitor_id',
        ),
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Visitor'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'VisitorName' => array(
            'className'     => 'VisitorName',
            'foreignKey'    => 'visitor_id',
            'conditions' => array('is_trash' => 0)
        ),
    );
}
