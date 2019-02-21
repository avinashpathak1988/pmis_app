<?php
App::uses('AppModel', 'Model');
class InPrisonPunishment extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'InternalPunishment' => array(
            'className' => 'InternalPunishment',
            'foreignKey' => 'internal_punishment_id',
        ),
        'DisciplinaryProceeding' => array(
            'className' => 'DisciplinaryProceeding',
            'foreignKey' => 'disciplinary_proceeding_id',
        ),
    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'InPrisonPunishment'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'InPrisonPunishmentConfinement'   => array(
            'className'     => 'InPrisonPunishmentConfinement',
            'foreignKey'    => 'in_prison_punishment_id',
            //'conditions'    => array('model_name' => 'InPrisonPunishment'),
            'order'         => 'InPrisonPunishmentConfinement.id DESC',
            'limit'         => 1
        ),
    );

	public $validate = array(
		'Disciplinary_proceeding_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Offence name is required.'
			 ),
			),
		'internal_punishment_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Offence Date is required.'
			),
			),
		'punishment_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Offence Date is required.'
			),
			),

		
	);
}
