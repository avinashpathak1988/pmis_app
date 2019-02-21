<?php
App::uses('AppModel', 'Model');
class EarningRate extends AppModel {

	public $belongsTo = array(
        'EarningGrade' => array(
            'className' => 'EarningGrade',
            'foreignKey' => 'earning_grade_id',
        ),
        
    );

	public $validate = array(
		'earning_grade_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Earning grade name is required.'
			 ),
			
			),
		'amount' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Amount is required.'
			),
			),
		'start_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Start Date is required.'
			),
			),
		'end_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'End Date is required.'
			),
		),	
	);
}
