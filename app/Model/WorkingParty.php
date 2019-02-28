<?php
App::uses('AppModel', 'Model');
class WorkingParty extends AppModel {
	public $belongsTo = array(
        'Prison' => array(
            'className' => 'Prison',
            'foreignKey' => 'prison_id',
        ),
        'Officer' => array(
            'className' => 'User',
            'foreignKey' => 'officer_incharge',
        ),
        'EmploymentType' => array(
            'className' => 'EmploymentType',
            'foreignKey' => 'employment_type_id',
        )
    );
	public $validate = array(
		'start_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Start date is required.'
			),
		),	
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Name of working party is required.'
			),
		),
	);
}
