<?php
App::uses('AppModel', 'Model');
class DebtorRate extends AppModel {

	public $belongsTo = array(
        'Prison' => array(
            'className' => 'Prison',
            'foreignKey' => 'prison_id',
        ),
        
    );

	public $validate = array(
		'prison_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prison name is required.'
			 ),
			),
		'rate_val' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Rate value is required.'
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
