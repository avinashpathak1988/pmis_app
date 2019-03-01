<?php
App::uses('AppModel', 'Model');
class Courtattendance extends AppModel {
public $belongsTo = array(
        'Court' => array(
            'className' => 'Court',
            'foreignKey' => 'court_id',
            'conditions' => '',
            'fields' => array("Court.name"),
            'order' => ''
        ),
        'Magisterial' => array(
            'className' => 'Magisterial',
            'foreignKey' => 'magisterial_id',
            'conditions' => '',
            'fields' => array("Magisterial.name"),
            'order' => ''
        ),
    );
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'court_level_name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'production_warrent_no' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Production Warrent No required.'
			),
		),
		'attendance_date_time' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Attendance date and time required.'
			),
		),
		'court_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Court  required.'
			),
			),
		'magisterials_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Magisterial Area is required.'
			),
		),
        'case_no' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'case no is required.'
            ),
        ),
	);
}
