<?php
App::uses('AppModel', 'Model');
class EarningRatePrisoner extends AppModel {
	public $belongsTo = array(
        'EarningRate' => array(
            'className' => 'EarningRate',
            'foreignKey' => 'earning_rate_id',
        ),
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
        ),
        
    );
	

	public $validate = array(
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Priosner Number is required.'
			 ),
			),
		'earnig_rate_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Earning Grade is required.'
			),
			),
		'date_of_assignment' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Assignment/Promotion Date is required.'
			),
			),
		
	);
}
