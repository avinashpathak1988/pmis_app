<?php
App::uses('AppModel', 'Model');
class InPrisonOffenceCapture extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'InternalOffence' => array(
            'className' => 'InternalOffence',
            'foreignKey' => 'internal_offence_id',
        ),
        'RuleRegulation' => array(
            'className' => 'RuleRegulation',
            'foreignKey' => 'rule_regulation_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => false,
        	'conditions' => array('InPrisonOffenceCapture.offence_recorded_by = User.id')
        ),
    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'InPrisonOffenceCapture'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
    public $hasOne = array(
        'DisciplinaryProceeding'   => array(
            'className'     => 'DisciplinaryProceeding',
            'foreignKey'    => 'in_prison_offence_capture_id',
            'order'         => 'DisciplinaryProceeding.created DESC',
        ),
        'InPrisonPunishment'   => array(
            'className'     => 'InPrisonPunishment',
            'foreignKey'    => 'in_prison_offence_capture_id',
            'order'         => 'InPrisonPunishment.created DESC',
        ),
    );

	public $validate = array(
		'internal_offence_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Offence name is required.'
			 ),
			),
		'offence_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Offence Date is required.'
			),
			),
		
	);
}
