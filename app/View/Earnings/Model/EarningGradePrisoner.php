<?php
App::uses('AppModel', 'Model');
class EarningGradePrisoner extends AppModel {
	public $belongsTo = array(
        'EarningGrade' => array(
            'className' => 'EarningGrade',
            'foreignKey' => 'grade_id',
        ),
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
        ),
    );   
    
}
