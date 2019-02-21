<?php
App::uses('AppModel', 'Model');
class GatePass	  extends AppModel {
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'escort' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Escort is required.'
			),
		),
		'purpose' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Purpose is required.'
			),
		),
        'destination' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'Destination is required.'
            ),
        ),
        'gp_date' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'Date is required.'
            ),
        ),								
	);
}
