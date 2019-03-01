<?php
App::uses('AppModel', 'Model');
class Court extends AppModel {
public $belongsTo = array(
        'Courtlevel' => array(
            'className' => 'Courtlevel',
            'foreignKey' => 'courtlevel_id',
        ),
        'Magisterial' => array(
            'className' => 'Magisterial',
            'foreignKey' => 'magisterial_id',
        ),
         'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => array("State.name"),
            'order' => ''
        ),
         'District' => array(
            'className' => 'District',
            'foreignKey' => 'district_id',
            'conditions' => '',
            'fields' => array("District.name"),
            'order' => ''
        ),
        
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
				'message'=> 'Court Name required.'
			),
		),
		'court_code' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Court code required.'
			),
		),
		'court_level_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Court level required.'
			),
			),
		'magisterial_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Magisterial Area is required.'
			),
		),
        'physical_address' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'Physical Address is required.'
            ),
        ),
	);
}
