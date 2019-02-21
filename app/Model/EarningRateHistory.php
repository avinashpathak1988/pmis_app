<?php
App::uses('AppModel', 'Model');
class EarningRateHistory extends AppModel {

	public $belongsTo = array(
        'EarningGrade' => array(
            'className' => 'EarningGrade',
            'foreignKey' => 'earning_grade_id',
        ),
        
    );
	
}
