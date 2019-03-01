<?php
App::uses('AppModel','Model');

class PrisonerSpecialNeed extends AppModel{
	
       public $validate = array(
		'prison_station' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Prison Station is required !',
			),
		)          										
	);

	public $belongsTo = array(
        'SpecialCondition' => array(
            'className' 	=> 'SpecialCondition',
            'foreignKey' 	=> 'special_condition_id',
        ),
        'Disability' => array(
            'className' 	=> 'Disability',
            'foreignKey' 	=> 'type_of_disability',
        ),
    );
}
