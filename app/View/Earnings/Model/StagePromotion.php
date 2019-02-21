<?php
App::uses('AppModel', 'Model');
class StagePromotion extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'Stage_New' => array(
            'className' => 'Stage',
            'foreignKey' => 'new_stage_id',
        ),
        'Stage_Old' => array(
            'className' => 'Stage',
            'foreignKey' => 'old_stage_id',
        ),
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
        ),
    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'StagePromotion'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );

	public $validate = array(
		'new_stage_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Stage Name is required.'
			 ),
			),
		'promotion_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Promotion Date is required.'
			),
			),
		'comment' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Reason is required.'
			),
			),
		
		
	);
}
