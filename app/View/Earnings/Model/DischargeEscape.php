<?php
App::uses('AppModel', 'Model');
class DischargeEscape extends AppModel {
	public $belongsTo = array(
        'PrisonerSentenceDetail' => array(
            'className' => 'PrisonerSentenceDetail',
            'foreignKey' => 'sentence_id',
        ),
        
    );
	public $validate = array(
		'date_of_esacape' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Date of escape is required !',
			),
		),
		'date_of_esacape' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Time of escape is required !',
			),
		),
	);	
}