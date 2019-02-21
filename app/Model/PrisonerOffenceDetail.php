<?php
App::uses('AppModel','Model');

class PrisonerOffenceDetail extends AppModel{
	
	public $belongsTo = array(
        'Offence' => array(
            'className'     => 'Offence',
            'foreignKey'    => 'offence',
        ),
        'SectionOfLaw' => array(
            'className'     => 'SectionOfLaw',
            'foreignKey'    => 'section_of_law',
        ),  
    );
	public $validate = array(
	'name' => array(
		'notBlank' => array(
			'rule' => array('notBlank'),
			'message' => 'First Name is required !',
		),
	),	

	'gender_id' => array(
		'notBlank' => array(
			'rule' => array('notBlank'),
			'message' => 'Gender is required !',
		),
	    'rule1' => array(
	        'rule' => array('numeric'),
	        'message' => 'Gender should be numeric !',
	    ),            
	),	
	  										
	);
}
