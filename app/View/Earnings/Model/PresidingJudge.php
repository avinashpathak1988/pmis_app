<?php
App::uses('AppModel', 'Model');
class PresidingJudge	  extends AppModel {
	public $belongsTo = array(
        'Magisterial' => array(
            'className' => 'Magisterial',
            'foreignKey' => 'magisterial_id',
        ),
        'Court' => array(
            'className' => 'Court',
            'foreignKey' => 'court_id',
        )
    );

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'name is required.'
			),
		)								
	);       	
}
