<?php
App::uses('AppModel', 'Model');
class StageDemotion extends AppModel {
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
        
    );

	public $validate = array(
		'new_stage_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Stage name is required.'
			 ),
			),
		'demotion_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Demotion Date is required.'
			),
			),
		
	);
}
