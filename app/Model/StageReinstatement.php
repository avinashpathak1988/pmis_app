
<?php
App::uses('AppModel', 'Model');
class StageReinstatement extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'Stage' => array(
            'className' => 'Stage',
            'foreignKey' => 'stage_reinstated_to',
        )
        
        
    );

	public $validate = array(
		'stage_reinstated_to' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Stage Name is required.'
			 ),
			),
		'reinstatement_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Promotion Date is required.'
			),
			),
		'probationary_period' =>array(
				'notBlank' =>array(
					'rule' 		=>array('notBlank'),
					'message' 	=> 'Probationary Period is required'	
					),
			),
		'comment' =>array(
				'notBlank' =>array(
					'rule' 		=>array('notBlank'),
					'message' 	=> 'Reason is required'	
					),
			),
	);
}
