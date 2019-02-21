<?php
App::uses('AppModel','Model');

class PrisonerSentenceDetail extends AppModel{
	
   public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),  
    );
   public $virtualFields = array(
		'sentence' => 'CONCAT(PrisonerSentenceDetail.years, " Years", PrisonerSentenceDetail.months, " Months",PrisonerSentenceDetail.days," Days" )'
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
