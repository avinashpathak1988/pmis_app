<?php
App::uses('AppModel', 'Model');
class StageAssign extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'Stage' => array(
            'className' => 'Stage',
            'foreignKey' => 'stage_id',
        ),
       
        
    );

	public $validate = array(
		'stage_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Stage Name is required.'
			 ),
			),
		'date_of_assign' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Date of assign is required.'
			),
			),
		
	);
}
