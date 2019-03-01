<?php
App::uses('AppModel', 'Model');
class StageHistory extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'Stage' => array(
            'className' => 'Stage',
            'foreignKey' => 'stage_id',
        ),
       
        
    );

	
}
